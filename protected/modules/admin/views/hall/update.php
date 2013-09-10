<?php
/* @var $this HallController */
/* @var $model Hall */

$this->breadcrumbs=array(
	'Halls'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Hall', 'url'=>array('index')),
	array('label'=>'Create Hall', 'url'=>array('create')),
	array('label'=>'View Hall', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Hall', 'url'=>array('admin')),
);
?>

<h1>Update Hall <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>