<?php
/* @var $this ServiceController */
/* @var $model Service */

$this->breadcrumbs=array(
	'Services'=>array('index'),
	$model->name,
);

?>

<h1>Просмотр услуги #<?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
			'value' => $model->id,
		),
		array(
			'name'=>'status',
			'value' => Service::$statusNames[$model->status],
		),
		array(
			'name'=>'name',
			'value' => $model->name,
		),
		array(
			'name'=>'color',
			'type'=>'raw',
			'value' => CHtml::tag('span', array('style'=>'display:block; width:25px; height:25px; background:'.$model->color.';')),
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
