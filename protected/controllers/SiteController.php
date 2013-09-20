<?php

class SiteController extends FrontController
{
	public function beforeAction($action)
	{
		Yii::import('application.components.maps.DateMap');
		return parent::beforeAction($action);
	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex($id=null, $time=null)
	{
		$time = intval($time);
		$checkedTime = empty($time) ? time() : $time;

		$this->layout = '//layouts/front';
		$this->pageTitle = 'Расписание';
		$this->moduleId = array('Calendar');
		$this->styles = array('calendar');
		$this->bodyClass = array('calendar', 'creative');

		$centers = Center::model()->findAllByAttributes(array('status'=>Center::STATUS_ACTIVE), array('index'=>'id'));
		$id = intval($id);

		if (empty($id)) {
			$current = @reset($centers);
		} else {
			if (empty($centers[$id])) {
				throw new CHttpException(404);
			}
			$current = $centers[$id];
		}

		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'center_id'=>$current->id), array('index'=>'id'));
		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));

		$dayStart = strtotime('TODAY', $checkedTime);
		$dayEnd = $dayStart + 86400;

		$events = Event::getByTime($dayStart, $dayEnd, $current->id);


		$this->render('index', array(

			'current' => $current,
			'centers' => $centers,
			'services' => $services,
			'halls' => $halls,

			'checkedTime' => $checkedTime,
			'events' => $events,
		));
	}

	/**
	 * Список событий
	 * @throws CHttpException
	 */
	public function actionAxEvents()
	{
		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();
		if (!$request->getIsAjaxRequest()) {
			throw new CHttpException(400);
		}

		$centerId = intval($request->getParam('center_id'));
		$day = intval($request->getParam('day_timestamp'));

		$center = Center::model()->findByPk($centerId);
		if ( $center===null || $center->status != Center::STATUS_ACTIVE ) {
			throw new CHttpException(404);
		}

		$dayStart = strtotime('TODAY', $day);

		$events = Event::getByTime($dayStart, $dayStart+86400, $center->id);
		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));
		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'center_id'=>$center->id), array('index'=>'id'));

		$html = $this->renderPartial('_events', array('halls'=>$halls, 'events'=>$events, 'services'=>$services), true);

		Yii::app()->end( json_encode(array('html'=>$html)) );
	}

	/**
	 * Детальная инфа по событию
	 */
	public function actionAxEvent()
	{
		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();
		if (!$request->getIsAjaxRequest()) {
			throw new CHttpException(400);
		}

		$eventId = intval($request->getParam('event_id'));
		$event = Event::model()->findByPk($eventId);
		if ($event===null) {
			throw new CHttpException(404);
		}

		$html = $this->renderPartial('_event', array('event'=>$event), true);

		Yii::app()->end( json_encode(array('html'=>$html)) );
	}

	/**
	 * Возвращает список активных дней в месяце
	 */
	public function actionAxActiveDays()
	{
		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();
		if (!$request->getIsAjaxRequest()) {
			throw new CHttpException(400);
		}

		$monthTime = intval($request->getParam('current_month'));
		$centerId = intval($request->getParam('center_id'));
		$subId = intval($request->getParam('sub_id'));

		$center = Center::model()->findByPk($centerId);
		if ( $center===null || $center->status != Center::STATUS_ACTIVE ) {
			throw new CHttpException(404);
		}

		$monthTime = DateMap::currentMonth($monthTime);
		$nextMonthTime = DateMap::getNextMonth($monthTime);

		$events = Event::getByTime($monthTime, $nextMonthTime, $center->id, $subId);

		$data = array();
		// начинаем выкашивать используемые
		/** @var $event Event */
		foreach ($events as $event) {
			$dayNumber = date('j', $event->start_time);
			if (!isset($data[$dayNumber])) {
				$data[$dayNumber] = $monthTime + ($dayNumber-1)*86400;
			}
		}
		$result = array_values($data);
		Yii::app()->end( json_encode(array('days'=>$result), JSON_NUMERIC_CHECK) );
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if ( empty($error['message']) && isset(Config::$errors[ $error['code'] ]) ) {
				$error['message'] = Config::$errors[$error['code']];
			}

			if(Yii::app()->getRequest()->getIsAjaxRequest()) {
				Yii::app()->end( json_encode( array('error'=>$error['code'], 'message'=>$error['message']), JSON_NUMERIC_CHECK ) );
			} else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout = 'empty';
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}