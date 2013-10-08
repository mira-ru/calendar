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
	public function actionIndex($center_id, $service_id, $direction_id, $time)
	{
		$time = intval($time);
		$checkedTime = empty($time) ? time() : $time;
		$checkedTime = DateMap::currentDay($checkedTime);
		$serviceId = intval($service_id);
		$directionId = intval($direction_id);

		$this->layout = '//layouts/front';
		$this->pageTitle = 'Расписание';
		$this->moduleId = array('Calendar');
		$this->bodyClass = array('calendar');

		$centers = Center::model()->findAllByAttributes(
			array('status'=>Center::STATUS_ACTIVE),
			array(
				'index'=>'id',
//				'order'=>'position ASC',
			)
		);

		// Находим текущий центр
		$center_id = intval($center_id);
		if (empty($center_id)) {
			$current = @reset($centers);
		} else {
			if (empty($centers[$center_id])) {
				throw new CHttpException(404);
			}
			$current = $centers[$center_id];
		}

		$this->bodyClass[] = 'center-'.$current->id;
		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));

		$dayEnd = $checkedTime + 86400;
		$events = Event::getByTime($checkedTime, $dayEnd, $current->id);

		// Список активных дней в месяце
		$currentMonth = DateMap::currentMonth($checkedTime);
		$nextMonth = DateMap::nextMonth($checkedTime);

		// Список активных услуг на месяц
		$services = Service::getActiveByTime($currentMonth, $nextMonth, $current->id);

		// проверка наличия выбранной услуги
		if (!empty($serviceId)) {
			$directionId = 0;
			if (empty($services[$serviceId])) {
				throw new CHttpException(404);
			}
		}
		// проверка наличия выбранного направления
		$checkedDirection = null;
		if (!empty($directionId)) {
			$checkedDirection = Direction::model()->findByPk($directionId);
			if ($checkedDirection===null) {
				throw new CHttpException(404);
			}
		}
		$activeDays = Event::getActiveDays($currentMonth, $nextMonth, $current->id, $directionId, $serviceId);


		$this->render('index', array(

			'current' => $current,
			'directionId' => $directionId,
			'serviceId' => $serviceId,
			'centers' => $centers,
			'services' => $services,
			'halls' => $halls,
			'checkedDirection' => $checkedDirection,

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

		$dayTime = intval($request->getParam('day'));
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

		$monthTime = DateMap::currentMonth($dayTime);
		$nextMonthTime = DateMap::nextMonth($monthTime);

		$data = Event::getActiveDays($monthTime, $nextMonthTime, $center->id, $directionId, $serviceId);

		$result = array_values($data);
		Yii::app()->end( json_encode(array('days'=>$result), JSON_NUMERIC_CHECK) );
	}

	public function actionAxMasterInfo()
	{
		$this->renderPartial('_master');
	}

	public function actionAxEventInfo()
	{
		$this->renderPartial('_eventinfo');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->layout = '//layouts/error';
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