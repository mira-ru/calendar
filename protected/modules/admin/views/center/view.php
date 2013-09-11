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
			'name'=>'name',
			'value' => $model->name,
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
