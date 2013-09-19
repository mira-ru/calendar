<?php

class SiteController extends FrontController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
//			'captcha'=>array(
//				'class'=>'CCaptchaAction',
//				'backColor'=>0xFFFFFF,
//			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
//			'page'=>array(
//				'class'=>'CViewAction',
//			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex($id=null, $time=null)
	{
		Yii::import('application.components.maps.DateMap');

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

		$events = Event::getByTime($dayStart, $dayEnd);


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
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if ( empty($error['message']) && isset(Config::$errors[ $error['code'] ]) ) {
				$error['message'] = Config::$errors[$error['code']];
			}

			if(Yii::app()->getRequest()->getIsAjaxRequest())
				echo $error['message'];
			else
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