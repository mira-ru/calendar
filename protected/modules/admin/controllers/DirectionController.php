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

				$url = $this->createUrl('index', array('Direction[id]'=>$model->id));
				$this->redirect($url);
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
				$url = $this->createUrl('index', array('Direction[id]'=>$model->id));

				$this->redirect($url);
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
		if(!isset($_GET['ajax'])) {
			$url = isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->getUser()->getReturnUrl(array('index'));
			$this->redirect($url);
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		Yii::app()->getUser()->setReturnUrl(Yii::app()->request->getRequestUri());

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

	public function actionCsv()
	{
		$model=new Direction('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Direction']))
			$model->attributes=$_GET['Direction'];

		$dataProvider = $model->search();

		$criteria = $dataProvider->getCriteria();
		// Добавим актуальность юзеров
		$criteria->join .= ' INNER JOIN event as e ON e.direction_id=t.id AND e.start_time>'.time();
		$criteria->distinct = true;
		$criteria->limit = -1;
		$criteria->offset = 0;
		$criteria->order = 't.id ASC';

		/** @var $data Direction[] */
		$data = Direction::model()->findAll($criteria);

		$forPrint = array();
		$forPrint[] = array('ID', 'Название направления', 'Описание', 'Видео', 'Фото');
		foreach($data as $direction) {
			$forPrint[] = array(
				$direction->id,
				$direction->name,
				empty($direction->desc) ? "Нет" : "Есть",
				empty($direction->url) ? "Нет" : "Есть",
				empty($direction->photo_url) ? "Нет" : "Есть",
			);
		}

		$csv = StrUtil::arrayToCsv($forPrint);

		ob_clean();
		header('Content-type: text/csv');
		header('Content-disposition: attachment;filename=DirectionInfo.csv');
		echo $csv;
		ob_flush();
		die();
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
