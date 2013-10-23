<?php

class SiteController extends FrontController
{
	public function beforeAction($action)
	{
		return parent::beforeAction($action);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex($class_id=null, $model_id=null, $time=null)
	{
		$time = intval($time);
		$currentTime = empty($time) ? time() : $time;
		$currentTime = DateMap::currentDay($currentTime);

		// Приведение параметров
		if ($class_id === null) { $class_id = Center::MODEL_TYPE; }
		if (!isset(Config::$modelMap[$class_id])) { throw new CHttpException(400); }

		$class = Config::$modelMap[ $class_id ];

		// Приведение ID модели
		if ($model_id === null) {
			if ($class === 'Center') {
				$model_id = Center::getFirstId();
			} else {
				throw new CHttpException(400);
			}
		}

		$model = $class::model()->findByPk($model_id);
		if ($model === null) { throw new CHttpException(404); }

		$config = Config::mapRequestParams($model);
		/**
		 * @var $centerId integer
		 * @var $serviceId integer
		 * @var $directionId integer
		 * @var $userId integer
		 * @var $hallId integer
		 */
		extract($config);

		$centers = Center::model()->findAllByAttributes(
			array('status'=>Center::STATUS_ACTIVE),
			array(
				'index'=>'id',
				'order'=>'position ASC',
			)
		);

		if ( !empty($centerId) && empty($centers[$centerId])) { throw new CHttpException(404); }

		// Список активных дней в месяце
		$currentMonth = DateMap::currentMonth($currentTime);
		$nextMonth = DateMap::nextMonth($currentTime);

		// Список активных услуг на месяц
		$services = Service::getActiveByTime($currentMonth, $nextMonth, $centerId);
		if ( !empty($serviceId) && empty($services[$serviceId]) ) { throw new CHttpException(404); }


		$viewType = Config::getViewType($model);

		if ($viewType == Config::VIEW_MONTH) {
			// вид списком
			$events = Event::getByTime($currentMonth, $nextMonth, $centerId, $directionId, $serviceId, $userId, $hallId);
			$allServices = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE), array('index'=>'id'));

			$this->render('index/monthView', array(
				'model' => $model,
				'centerId' => $centerId,
				'centers' => $centers,
				'services' => $services,
				'events' => $events,
				'currentTime' => $currentTime,
				'currentMonth' => $currentMonth,
				'nextMonth' => $nextMonth,
				'allServices' => $allServices,
			));
			return;

		} elseif ( $viewType == Config::VIEW_WEEK ) {

			$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));
			$timeStart = DateMap::currentWeek($currentTime);
			$timeEnd = DateMap::nextWeek($currentTime);

			$activeDays = Event::getActiveDays($timeStart, $timeEnd, $centerId, $directionId, $serviceId, $userId, $hallId);

			$events = Event::getByTime($timeStart, $timeEnd, $centerId, $directionId, $serviceId, $userId, $hallId);
			$allServices = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE), array('index'=>'id'));

			$this->render('index/weekView', array(

				'model' => $model,
				'centerId' => $centerId,

				'centers' => $centers,
				'services' => $services,
				'halls' => $halls,
				'events' => $events,
				'activeDays' => $activeDays,
				'allServices' => $allServices,

				'currentTime' => $currentTime,
				'currentMonth' => $currentMonth,
				'nextMonth' => $nextMonth,
			));
			return;

		} elseif ( $viewType == Config::VIEW_DAY ) {
			$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));
			$timeStart = $currentTime;
			$timeEnd = $currentTime + DateMap::TIME_DAY;

			$activeDays = Event::getActiveDays($currentMonth, $nextMonth, $centerId, $directionId, $serviceId, $userId, $hallId);
			$events = Event::getByTime($timeStart, $timeEnd, $centerId, $directionId, $serviceId, $userId, $hallId);

			$this->render('index/dayView', array(

				'model' => $model,
				'centerId' => $centerId,

				'centers' => $centers,
				'services' => $services,
				'halls' => $halls,
				'events' => $events,
				'activeDays' => $activeDays,

				'currentTime' => $currentTime,
				'currentMonth' => $currentMonth,
				'nextMonth' => $nextMonth,
			));

			return;
		}
		throw new CHttpException(500);


	}

	/**
	 * Список событий за день
	 * @throws CHttpException
	 */
	public function actionAxEvents()
	{
		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();
		if (!$request->getIsAjaxRequest()) { throw new CHttpException(400); }

		$day = intval($request->getParam('day'));
		$type = $request->getParam('type');
		$modelId = intval($request->getParam('item'));

		$classId = array_search($type, Config::$routeMap);
		if ($classId === false) { throw new CHttpException(400); }
		if ( empty($modelId) ) { throw new CHttpException(400); }

		// Приведение параметров
		if (!isset(Config::$modelMap[$classId])) { throw new CHttpException(400); }
		$class = Config::$modelMap[ $classId ];

		$model = $class::model()->findByPk($modelId);
		if ($model === null) { throw new CHttpException(404); }

		$config = Config::mapRequestParams($model);
		/**
		 * @var $centerId integer
		 * @var $serviceId integer
		 * @var $directionId integer
		 * @var $userId integer
		 * @var $hallId integer
		 */
		extract($config);

		$currentTime = DateMap::currentDay($day);

		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE), array('index'=>'id'));

		$viewType = Config::getViewType($model);

		if ( $viewType == Config::VIEW_MONTH ) {
			// вид списком
			$days = '';
			$timeStart = DateMap::currentWeek($currentTime);
			$timeEnd = DateMap::nextWeek($currentTime);
			$events = Event::getByTime($timeStart, $timeEnd, $centerId, $directionId, $serviceId, $userId, $hallId);

			$html = $this->renderPartial('ajax/_monthEvents', array(
				'model'=>$model,
				'events'=>$events,
				'centerId'=>$centerId,
				'currentTime'=>$currentTime,
				'allServices'=>$services,
			), true);
		} elseif ( $viewType == Config::VIEW_WEEK) {
			$timeStart = DateMap::currentWeek($currentTime);
			$timeEnd = DateMap::nextWeek($currentTime);

			$activeDays = Event::getActiveDays($timeStart, $timeEnd, $centerId, $directionId, $serviceId, $userId, $hallId);
			$days = $this->renderPartial('ajax/_daysWeek', array('currentTime'=>$currentTime, 'activeDays'=>$activeDays), true);

			$events = Event::getByTime($timeStart, $timeEnd, $centerId, $directionId, $serviceId, $userId, $hallId);

			$html = $this->renderPartial('ajax/_weekEvents', array(
				'events'=>$events,
				'services'=>$services,
				'currentTime'=>$currentTime,
			), true);
		} elseif ( $viewType == Config::VIEW_DAY ) {
			$timeStart = $currentTime;
			$timeEnd = $currentTime + DateMap::TIME_DAY;

			$monthTime = DateMap::currentMonth($currentTime);
			$nextMonthTime = DateMap::nextMonth($monthTime);
			$activeDays = Event::getActiveDays($monthTime, $nextMonthTime, $centerId, $directionId, $serviceId, $userId, $hallId);

			$days = $this->renderPartial('ajax/_daysMonth', array('currentTime'=>$currentTime, 'activeDays'=>$activeDays), true);

			$events = Event::getByTime($timeStart, $timeEnd, $centerId, $directionId, $serviceId, $userId, $hallId);
			$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));

			$html = $this->renderPartial('ajax/_dayEvents', array(
				'halls'=>$halls,
				'events'=>$events,
				'services'=>$services,
			), true);
		} else {
			throw new CHttpException(500);
		}

		$nextWeek = DateMap::nextWeek($currentTime);
		$prevWeek = DateMap::prevWeek($currentTime);

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
		if ($event===null) { throw new CHttpException(404); }

		$day = intval($request->getParam('day'));
		$type = $request->getParam('type');
		$modelId = intval($request->getParam('item'));

		$classId = array_search($type, Config::$routeMap);
		if ($classId === false) { throw new CHttpException(400); }
		if ( empty($modelId) ) { throw new CHttpException(400); }

		// Приведение параметров
		if (!isset(Config::$modelMap[$classId])) { throw new CHttpException(400); }
		$class = Config::$modelMap[ $classId ];

		$model = $class::model()->findByPk($modelId);
		if ($model === null) { throw new CHttpException(404); }

		$html = $this->renderPartial('ajax/_event', array(
			'event'=>$event,
			'day'=>$day,
			'model'=>$model,
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
				$this->render('error',array('error' => $error));
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