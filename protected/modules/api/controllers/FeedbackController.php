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
			RESTfulHelper::sendResponse(500, 'Error: Parameter <b>id</b> is missing' );

		$model = Feedback::model()->findByPk((int) $id);

		if(is_null($model))
			RESTfulHelper::sendResponse(404, 'No Item found with id '.$_GET['id']);
		else
			RESTfulHelper::sendResponse(200, CJSON::encode($model));
	}

	/**
	 * Просмотр всех записей
	 */
	public function actionList()
	{
		if ( count($_GET) > 0 ) {

			$filter_vars = array();

			foreach($_GET as $var=>$value) {

				if ( Feedback::model()->hasAttribute($var) && $value)
					$filter_vars[$var] = $value;
				else
					RESTfulHelper::sendResponse(500, "Parameter <b>$var</b> is not allowed");
			}

			$models = Feedback::model()->findAllByAttributes($filter_vars);

		} else
			$models = Feedback::model()->findAll();


		if(empty($models))
			RESTfulHelper::sendResponse(200, 'No items where found');

		$rows = array();
		foreach($models as $model)
			$rows[] = $model->attributes;

		RESTfulHelper::sendResponse(200, CJSON::encode($rows));
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
				RESTfulHelper::sendResponse(500, "Parameter <b>$var</b> is not allowed");
		}

		if($model->save())
			RESTfulHelper::sendResponse(200, CJSON::encode($model));
		else
			RESTfulHelper::sendResponse(500, $this->_generateErrMsg($model, "Couldn't create object"));
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
			RESTfulHelper::sendResponse(400, "Error: Didn't find any model with ID <b>$id</b>.");

		foreach($put_vars as $var=>$value) {
			if($model->hasAttribute($var))
				$model->$var = $value;
			else {
				RESTfulHelper::sendResponse(500, "Parameter <b>$var</b> is not allowed>");
			}
		}

		if($model->save())
			RESTfulHelper::sendResponse(200, CJSON::encode($model));
		else
			RESTfulHelper::sendResponse(500, $this->_generateErrMsg($model, "Couldn't update object"));

	}

	/**
	 * Удаление записи
	 * @param $id
	 */
	public function actionDelete($id)
	{
		$model = Feedback::model()->findByPk((int) $id);
		if($model === null)
			RESTfulHelper::sendResponse(400, "Error: Didn't find any model with ID <b>$id</b>.");

		$num = $model->delete();
		if($num>0)
			RESTfulHelper::sendResponse(200, $num);    //this is the only way to work with backbone
		else
			RESTfulHelper::sendResponse(500, "Error: Couldn't delete model with ID <b>$id</b>.");
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