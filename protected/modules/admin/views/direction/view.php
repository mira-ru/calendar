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
			'name'=>'center_id',
			'type'=>'raw',
			'value'=>empty($model->center_id) ? 'Центр не указан' : $model->center->name,
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
