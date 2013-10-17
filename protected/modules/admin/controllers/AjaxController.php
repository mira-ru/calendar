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
			. " WHERE t.name LIKE '%" . CHtml::encode($term) . "%' AND t.status=".User::STATUS_ACTIVE
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

	/**
	 * Получение контента дропдауна услуг по центру
	 */
	public function actionAxService()
	{
		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();

		$centerId = intval($request->getParam('center_id'));
		if (empty($centerId)) {
			Yii::app()->end();
		}

		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'center_id'=>$centerId));

		echo CHtml::tag('option', array('value'=>''), 'Выберите услугу');
		foreach ($services as $service) {
			echo CHtml::tag('option', array('value'=>$service->id), $service->name);
		}

		Yii::app()->end();
	}

	/**
	 * Получение контента дропдауна направлений по услуге
	 */
	public function actionAxDirection()
	{
		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();

		$serviceId = intval($request->getParam('service_id'));
		if (empty($serviceId)) {
			Yii::app()->end();
		}

		$directions = Direction::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'service_id'=>$serviceId));

		echo CHtml::tag('option', array('value'=>''), 'Выберите направление');
		foreach ($directions as $direction) {
			echo CHtml::tag('option', array('value'=>$direction->id), $direction->name);
		}

		Yii::app()->end();
	}


}
