<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>Просмотр мастера #<?php echo $model->name; ?></h1>

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
			'name'=> 'create_time',
			'value'=>date("d.m.Y", $model->create_time),
		),
		array(
			'name'=> 'update_time',
			'value'=>date("d.m.Y", $model->update_time),
		),
	),
)); ?>
