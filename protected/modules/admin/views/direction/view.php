<?php
/* @var $this DirectionController */
/* @var $model Direction */

?>

<h1>Просмотр направления #<?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
			'value' => $model->id,
		),
		array(
			'name'=>'status',
			'value' => User::$statusNames[$model->status],
		),
		array(
			'name'=>'name',
			'value' => $model->name,
		),
		array(
			'name'=>'service_id',
			'sortable' => false,
			'type'=>'raw',
			'value'=>empty($model->service_id) ? 'Услуга не указана' : $model->service->name,
		),
		array(
			'name'=>'url',
			'value' => $model->url,
		),
		array(
			'name'=>'center_id',
			'type'=>'raw',
			'value'=>empty($model->center_id) ? 'Центр не указан' : $model->center->name,
		),
		array(
			'name'=>'desc',
			'type'=>'raw',
			'value' => $model->desc,
		),
		array(
			'name'=>'short_desc',
			'type'=>'raw',
			'value' => $model->short_desc,
		),
		array(
			'name'=> 'create_time',
			'value'=>date("d.m.Y", $model->create_time),
		),
		array(
			'name'=> 'update_time',
			'value'=>date("d.m.Y", $model->update_time),
		),
	),
)); ?>

<?php
$this->widget('widgets.CustomGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->getEventsProvider(),
	'columns'=>array(
		array(
			'name'=>'id',
			'sortable' => false,
			'value' => '$data->id',
		),
		array(
			'name'=>'users',
			'sortable' => false,
			'type' => 'raw',
			'value' => 'empty($data->users) ? \'Мастера не указаны\' : $data->renderAdminUsers()',
		),
		array(
			'name'=>'service_id',
			'sortable' => false,
			'type' => 'raw',
			'value' => 'empty($data->service) ? \'Услуги не указаны\' : CHtml::link($data->service->name, Yii::app()->controller->createUrl(\'/admin/event/index\', array(\'Event[service_id]\'=> $data->service->id) ))',
		),
		array(
			'name'=>'hall_id',
			'sortable' => false,
			'type' => 'raw',
			'value' => 'empty($data->hall) ? \'Зал не указан\' : CHtml::link($data->hall->name, Yii::app()->controller->createUrl(\'/admin/event/index\', array(\'Event[hall_id]\'=> $data->hall->id) ))',
		),
		array(
			'name'=>'center_id',
			'sortable' => false,
			'type' => 'raw',
			'value' => 'empty($data->center) ? \'Центр не выбран\' : CHtml::link($data->center->name, Yii::app()->controller->createUrl(\'/admin/event/index\', array(\'Event[center_id]\'=> $data->center->id) ))',
		),
		array(
			'name'=>'direction_id',
			'sortable' => false,
			'type' => 'raw',
			'value' => 'empty($data->direction) ? \'Направление не выбрано\' : CHtml::link($data->direction->name, Yii::app()->controller->createUrl(\'/admin/event/index\', array(\'Event[direction_id]\'=> $data->direction->id) ))',
		),
		array(
			'name'=>'day_of_week',
			'sortable' => false,
			'value' => 'DateMap::$smallDayMap[$data->day_of_week]',
		),
		array(
			'name'=> 'start_time',
			'sortable' => false,
			'value'=>'date("d.m.Y H:i:s", $data->start_time)',
		),
		array(
			'name'=> 'end_time',
			'sortable' => false,
			'value'=>'date("d.m.Y H:i:s", $data->end_time)',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
