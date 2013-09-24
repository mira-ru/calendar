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
		$this->bodyClass = array('calendar');


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
		$this->bodyClass[] = 'center-'.$current->id;

		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));

		$dayStart = strtotime('TODAY', $checkedTime);
		$dayEnd = $dayStart + 86400;

		$events = Event::getByTime($dayStart, $dayEnd, $current->id);

		// Список активных дней в месяце
		$currentMonth = DateMap::currentMonth($checkedTime);
		$nextMonth = DateMap::nextMonth($checkedTime);
		$activeDays = Event::getActiveDays($currentMonth, $nextMonth, $current->id);
		// Список активных услуг на месяц
		$services = Service::getActiveByTime($currentMonth, $nextMonth, $current->id);


		$this->render('index', array(

			'current' => $current,
			'centers' => $centers,
			'services' => $services,
			'halls' => $halls,

			'checkedTime' => $checkedTime,
			'events' => $events,
			'activeDays' => $activeDays,

			'currentMonth' => $currentMonth,
			'nextMonth' => $nextMonth,
		));
	}

	/**
	 * Список событий за день
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
		$day = intval($request->getParam('day'));
		$directionId = intval($request->getParam('activity_id'));
		$serviceId = intval($request->getParam('service_id'));

		if ($serviceId) {
			$directionId = null;
		}

		$center = Center::model()->findByPk($centerId);
		if ( $center===null || $center->status != Center::STATUS_ACTIVE ) {
			throw new CHttpException(404);
		}

		$dayStart = strtotime('TODAY', $day);

		$events = Event::getByTime($dayStart, $dayStart+86400, $center->id);

		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));
		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'center_id'=>$center->id), array('index'=>'id'));

		$html = $this->renderPartial('_ajaxEvents', array(
			'halls'=>$halls,
			'events'=>$events,
			'services'=>$services,
			'directionId'=>$directionId,
			'serviceId'=>$serviceId,
		), true);

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

		$monthTime = intval($request->getParam('month'));
		$centerId = intval($request->getParam('center_id'));
		$directionId = intval($request->getParam('activity_id'));
		$serviceId = intval($request->getParam('service_id'));

		if ($serviceId) {
			$directionId = null;
		}

		$center = Center::model()->findByPk($centerId);
		if ( $center===null || $center->status != Center::STATUS_ACTIVE ) {
			throw new CHttpException(404);
		}

		$monthTime = DateMap::currentMonth($monthTime);
		$nextMonthTime = DateMap::nextMonth($monthTime);

		$data = Event::getActiveDays($monthTime, $nextMonthTime, $center->id, $directionId, $serviceId);

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