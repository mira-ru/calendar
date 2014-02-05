<?php

class ReportController extends AdminController
{

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Report('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Report']))
			$model->attributes=$_GET['Report'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
}
