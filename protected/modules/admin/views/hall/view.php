<?php
/* @var $this HallController */
/* @var $model Hall */

$this->breadcrumbs=array(
	'Halls'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Hall', 'url'=>array('index')),
	array('label'=>'Create Hall', 'url'=>array('create')),
	array('label'=>'Update Hall', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Hall', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Hall', 'url'=>array('admin')),
);
?>

<h1>Просмотр зала #<?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
			'value' => $model->id,
		),
		array(
			'name'=>'status',
			'value' => Hall::$statusNames[$model->status],
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
