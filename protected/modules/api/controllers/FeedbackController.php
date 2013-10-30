<?php

class FeedbackController extends ApiController
{
	/**
	 * Просмотр конкретной записи
	 * @param null $id
	 */
	public function actionView($id=null)
	{
		if( !$id )
			$this->response(500, 'Error: Parameter <b>id</b> is missing' );

		$model = Feedback::model()->findByPk((int) $id);

		if(is_null($model))
			$this->response(404, 'No Item found with id '.$_GET['id']);
		else
			$this->response(200, CJSON::encode($model));
	}

	/**
	 * Просмотр всех записей
	 */
	public function actionList()
	{
		$criteria = $this->genCriteria($_GET);
		$models = Feedback::model()->findAll($criteria);

		if(empty($models))
			$this->response(200, 'No items where found');

		$rows = array();
		foreach($models as $model)
			$rows[] = $model->attributes;

		$this->response(200, CJSON::encode($rows));
	}

	/**
	 * Создание новой записи
	 */
	public function actionCreate()
	{
		$model = new Feedback();

		foreach($_POST as $var=>$value) {

			if($model->hasAttribute($var))
				$model->$var = $value;
			else
				$this->response(500, "Parameter <b>$var</b> is not allowed");
		}

		if($model->save())
			$this->response(200, CJSON::encode($model));
		else
			$this->response(500, $this->_generateErrMsg($model, "Couldn't create object"));
	}

	/**
	 * Редактирование записи
	 * @param $id
	 */
	public function actionUpdate($id)
	{
		$json = Yii::app()->request->getRawBody();
		$put_vars = json_decode($json);

		$model = Feedback::model()->findByPk((int) $id);
		if($model === null)
			$this->response(400, "Error: Didn't find any model with ID <b>$id</b>.");

		foreach($put_vars as $var=>$value) {
			if($model->hasAttribute($var))
				$model->$var = $value;
			else {
				$this->response(500, "Parameter <b>$var</b> is not allowed>");
			}
		}

		if($model->save())
			$this->response(200, CJSON::encode($model));
		else
			$this->response(500, $this->_generateErrMsg($model, "Couldn't update object"));

	}

	/**
	 * Удаление записи
	 * @param $id
	 */
	public function actionDelete($id)
	{
		$model = Feedback::model()->findByPk((int) $id);
		if($model === null)
			$this->response(400, "Error: Didn't find any model with ID <b>$id</b>.");

		$num = $model->delete();
		if($num>0)
			$this->response(200, $num);    //this is the only way to work with backbone
		else
			$this->response(500, "Error: Couldn't delete model with ID <b>$id</b>.");
	}

	/**
	 * Генерация сообщения об ошибке валидации модели
	 * @param $model
	 * @param $text
	 * @return string
	 */
	private function _generateErrMsg($model, $text)
	{
		$msg = "<h1>Error</h1>";
		$msg .= $text;
		$msg .= "<ul>";
		foreach($model->errors as $attribute=>$attr_errors) {
			$msg .= "<li>Attribute: $attribute</li>";
			$msg .= "<ul>";
			foreach($attr_errors as $attr_error)
				$msg .= "<li>$attr_error</li>";
			$msg .= "</ul>";
		}
		$msg .= "</ul>";
		return $msg;
	}
}