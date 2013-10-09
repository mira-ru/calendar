<?php

class DirectionController extends AdminController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index', 'create', 'update', 'view' ,'delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Direction();

		if(isset($_POST['Direction']))
		{
			$model->attributes=$_POST['Direction'];

			$model->file = CUploadedFile::getInstance($model, 'file');
			if ($model->validate()) {
				if ($model->file instanceof CUploadedFile) {
					$file = $model->file->getTempName();
					$fileId = Yii::app()->image->putImage($file, $model->file->getName());
					if (empty($fileId)) {
						throw new CHttpException(500);
					}

					$model->image_id = $fileId;
				}
				$model->save(false);
				$this->redirect(array('index','id'=>$model->id));
			}
		}

		$centers = Center::model()->findAllByAttributes(array('status'=>Center::STATUS_ACTIVE));
		$services = array();

		$this->render('create',array(
			'model'=>$model,
			'centers'=>$centers,
			'services'=>$services,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Direction']))
		{
			$model->attributes=$_POST['Direction'];
			$model->file = CUploadedFile::getInstance($model, 'file');

			if ($model->validate()) {
				if ($model->file instanceof CUploadedFile) {
					$file = $model->file->getTempName();
					$fileId = Yii::app()->image->putImage($file, $model->file->getName());
					if (empty($fileId)) {
						throw new CHttpException(500);
					}

					$model->image_id = $fileId;
				}
				$model->save(false);
				$this->redirect(array('index','id'=>$model->id));
			}
		}

		$centers = Center::model()->findAllByAttributes(array('status'=>Center::STATUS_ACTIVE));
		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'center_id'=>$model->center_id));

		$this->render('update',array(
			'model'=>$model,
			'centers'=>$centers,
			'services'=>$services,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		$model->status = Direction::STATUS_DELETED;
		$model->save(false);

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Direction('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Direction']))
			$model->attributes=$_GET['Direction'];

		$centers = Center::model()->findAllByAttributes(array('status'=>Center::STATUS_ACTIVE));
		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE));

		$this->render('index',array(
			'model'=>$model,
			'centers' => $centers,
			'services' => $services,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Direction the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Direction::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
