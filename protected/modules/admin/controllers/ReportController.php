<?php

class ReportController extends AdminController
{

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', 'users'=>array('alexandrovna13')),
			array('deny', 'users'=>array('*')),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		FirePHP::getInstance()->fb(Yii::app()->user->name);
		$model=new Report('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Report']))
			$model->attributes=$_GET['Report'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
}
