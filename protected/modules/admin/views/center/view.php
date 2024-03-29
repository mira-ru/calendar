<?php
/* @var $this CenterController */
/* @var $model Center */

$this->breadcrumbs=array(
	'Centers'=>array('index'),
	$model->name,
);
?>

<h1>Просмотр центра #<?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
			'value' => $model->id,
		),
		array(
			'name'=>'status',
			'value' => Center::$statusNames[$model->status],
		),
		array(
			'name'=>'overview',
			'value' => Config::$viewNames[$model->overview],
		),
		array(
			'name'=>'detailed_view',
			'value' => Config::$viewNames[$model->detailed_view],
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
			'name'=> 'create_time',
			'value'=>date("d.m.Y", $model->create_time),
		),
		array(
			'name'=> 'update_time',
			'value'=>date("d.m.Y", $model->update_time),
		),
	),
)); ?>
