<?php

class UserController extends AdminController
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
		$model=new User;

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];

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

		$this->render('create',array(
			'model'=>$model,
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

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
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

		$this->render('update',array(
			'model'=>$model,
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
		$model->status = User::STATUS_DELETED;
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
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionCsv()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$dataProvider = $model->search();

		$criteria = $dataProvider->getCriteria();
		// Добавим актуальность юзеров
		$criteria->join .= ' INNER JOIN event_user as eu ON eu.user_id=t.id';
		$criteria->join .= ' INNER JOIN event as e ON e.template_id=eu.template_id AND e.start_time>'.time();
		$criteria->join .= ' INNER JOIN direction as d ON d.id=e.direction_id ';
		$criteria->join .= ' INNER JOIN center as c ON c.id=e.center_id';

		$criteria->select = 't.*, GROUP_CONCAT(DISTINCT c.name SEPARATOR ", ") as center_name, GROUP_CONCAT(DISTINCT d.name SEPARATOR ", ") as direction_name';
//		$criteria->distinct = true;

		$criteria->group = 't.id';
		$criteria->limit = -1;
		$criteria->offset = 0;
		$criteria->order = 't.id ASC';

		/** @var $data User[] */
		$data = User::model()->findAll($criteria);

		$forPrint = array();
		$forPrint[] = array('ID', 'ФИО', 'Описание', 'Видео', 'Фото', 'Центр', 'Направление');
		foreach($data as $user) {
			$forPrint[] = array(
				$user->id,
				$user->name,
				empty($user->desc) ? "Нет" : "Есть",
				empty($user->url) ? "Нет" : "Есть",
				empty($user->photo_url) ? "Нет" : "Есть",
				$user->center_name,
				$user->direction_name,
			);
		}

		$csv = StrUtil::arrayToCsv($forPrint);

		ob_clean();
		header('Content-type: text/csv');
		header('Content-disposition: attachment;filename=MasterInfo.csv');
		echo $csv;
		ob_flush();
		die();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
