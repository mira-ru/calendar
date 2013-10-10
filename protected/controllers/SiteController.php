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
				'order'=>'position ASC',
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

		if (!empty($directionId)) {
			$timeStart = DateMap::currentWeek($checkedTime);
			$timeEnd = DateMap::nextWeek($checkedTime);

			$activeDays = Event::getActiveDays($timeStart, $timeEnd, $current->id, $directionId, $serviceId);

		} else { // вид по дням
			$timeStart = $checkedTime;
			$timeEnd = $checkedTime + DateMap::TIME_DAY;

			$activeDays = Event::getActiveDays($currentMonth, $nextMonth, $current->id, $directionId, $serviceId);
		}

		$events = Event::getByTime($timeStart, $timeEnd, $current->id, $directionId, $serviceId);


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

		$dayStart = DateMap::currentDay($day);

		if ($serviceId) { $directionId = null; }

		$center = Center::model()->findByPk($centerId);
		if ( $center===null || $center->status != Center::STATUS_ACTIVE ) {
			throw new CHttpException(404);
		}

		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'center_id'=>$center->id), array('index'=>'id'));

		// выбрано направление - недельный вид
		if (!empty($directionId)) {
			$timeStart = DateMap::currentWeek($day);
			$timeEnd = DateMap::nextWeek($day);

			$activeDays = Event::getActiveDays($timeStart, $timeEnd, $center->id, $directionId, $serviceId);
			$days = $this->renderPartial('index/_daysWeek', array('checkedTime'=>$day, 'activeDays'=>$activeDays), true);

			$events = Event::getByTime($timeStart, $timeEnd, $center->id, $directionId, $serviceId);

			$html = $this->renderPartial('ajax/_weekEvents', array(
				'events'=>$events,
				'services'=>$services,
				'checkedTime'=>$dayStart,
				'centerId'=>$centerId,
				'serviceId'=>$serviceId,
				'directionId'=>$directionId,
			), true);

		} else { // вид по дням
			$timeStart = $dayStart;
			$timeEnd = $dayStart + DateMap::TIME_DAY;

			$monthTime = DateMap::currentMonth($dayStart);
			$nextMonthTime = DateMap::nextMonth($monthTime);
			$activeDays = Event::getActiveDays($monthTime, $nextMonthTime, $center->id, $directionId, $serviceId);

			$days = $this->renderPartial('index/_daysMonth', array('checkedTime'=>$day, 'activeDays'=>$activeDays), true);

			$events = Event::getByTime($timeStart, $timeEnd, $center->id, $directionId, $serviceId);
			$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));

			$html = $this->renderPartial('ajax/_monthEvents', array(
				'halls'=>$halls,
				'events'=>$events,
				'services'=>$services,
			), true);
		}
		$nextWeek = DateMap::nextWeek($day);
		$prevWeek = DateMap::prevWeek($day);

		Yii::app()->end( json_encode(array('html'=>$html, 'days'=>$days, 'week'=>array('next'=>$nextWeek, 'prev'=>$prevWeek))) );
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

		$centerId = intval($request->getParam('center_id'));
		$day = intval($request->getParam('day'));
		$directionId = intval($request->getParam('activity_id'));
		$serviceId = intval($request->getParam('service_id'));

		$html = $this->renderPartial('ajax/_event', array(
			'event'=>$event,
			'centerId'=>$centerId,
			'day'=>$day,
			'directionId'=>$directionId,
			'serviceId'=>$serviceId,
		), true);

		Yii::app()->end( json_encode(array('html'=>$html)) );
	}

	/**
	 * Отдача контента для popup мастера и направления
	 */
	public function actionAxPopup()
	{
		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();
		if (!$request->getIsAjaxRequest()) {
			throw new CHttpException(400);
		}

		$type = $request->getParam('type', '');
		$itemId = intval($request->getParam('item'));

		switch ($type) {
			case 'm': $class = 'User'; break;
			case 'a': $class = 'Direction'; break;
			default: throw new CHttpException(400);
		}

		$item = $class::model()->findByPk($itemId);
		if ($item === null) {
			throw new CHttpException(404);
		}

		if ($item instanceof User) {
			$this->renderPartial('ajax/_masterPopup', array('item'=>$item));
			Yii::app()->end();
		} elseif ($item instanceof Direction) {
			$this->renderPartial('ajax/_directionPopup', array('item'=>$item));
			Yii::app()->end();
		} else {
			throw new CHttpException(404);
		}

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