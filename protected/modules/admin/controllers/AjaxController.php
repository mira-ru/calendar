<?php

class AjaxController extends AdminController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'ajaxOnly',
		);
	}

	/**
	 * Автокомплит по юзерам
	 * @param $term
	 * @throws CHttpException
	 */
	public function actionAcUser($term)
	{
		$this->layout = false;

		$command = Yii::app()->db;

		$data = $command->createCommand(
			"SELECT t.id, t.name FROM `user` t"
			. " WHERE t.name LIKE '%" . CHtml::encode($term) . "%'"
			. " LIMIT 10"
		)->queryAll();
		$results = array();
		foreach($data as $record) {

			$results[] = array(
				'label' => $record['name'],
				'id'    => $record['id'],
			);
		}
		Yii::app()->end( json_encode($results, JSON_NUMERIC_CHECK) );
	}


}
