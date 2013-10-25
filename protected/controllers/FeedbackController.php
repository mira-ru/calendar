<?php

class FeedbackController extends FrontController
{
	public function actionIndex()
	{
		Yii::app()->getClientScript()->registerCssFile('/css/generated/feedback.css');
		$this->bodyClass = array('feedback');
		$this->layout = '//layouts/feedback';

		//print_r($messages);
		$this->render('index');

		return;

	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAxCreate()
	{
		$model = new Feedback;

		if(isset($_POST['text']))
		{
			$model->attributes=$_POST;
			if($model->save()){
				$msg = 'Ваш отзыв добавлен, спасибо :)';
				$error = false;
			} else {
				$msg = $model;
				$error = true;
			}

			die(CJSON::encode(array(
				'msg' => $msg,
				'error' => $error,
			)));
		}
	}

	public function actionAxGetMessages(){
		$status = (int)Yii::app()->request->getPost('status');
		$status = (!empty($status)) ? array("status" => $status) : array();

		$messages = Feedback::model()->findAllByAttributes(
			$status,
			array(
				'index'=>'id',
				'order'=>'id ASC',
			)
		);

		die(CJSON::encode(array(
			'msg' => $messages,
		)));
	}
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}