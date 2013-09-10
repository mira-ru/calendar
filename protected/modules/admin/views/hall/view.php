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

<h1>View Hall #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'create_time',
		'update_time',
	),
)); ?>
