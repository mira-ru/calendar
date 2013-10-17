<?php
class AjaxController extends FrontController
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
	 * Автокомплит для поисковой строки
	 * @param string $term
	 */
	public function actionSearch($term='')
	{
		$term .= '*';
		$term = addslashes($term);
		$sphinxQl = 'SELECT type_id, item_id FROM {{filter}} WHERE MATCH(\''.$term.'\') LIMIT 10';

		$data = Yii::app()->sphinx->createCommand($sphinxQl)->queryAll();

		$result = array();
		foreach ($data as $item) {
			$class = Config::$modelMap[$item['type_id']];
			$model = $class::model()->findByPk($item['item_id']);
			if ($model===null) { continue; }

			$result[] = array(
				'label' => $model->name,
				'type' => Config::$routeMap[$item['type_id']],
				'item' => $model->id,
			);
		}

		Yii::app()->end( json_encode($result, JSON_NUMERIC_CHECK) );
	}
}