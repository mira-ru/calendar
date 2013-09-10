<?php
/* @var $this CenterController */
/* @var $model Center */

$this->breadcrumbs=array(
	'Centers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Center', 'url'=>array('index')),
	array('label'=>'Manage Center', 'url'=>array('admin')),
);
?>

<h1>Create Center</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>