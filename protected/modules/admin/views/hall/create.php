<?php
/* @var $this HallController */
/* @var $model Hall */

$this->breadcrumbs=array(
	'Halls'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Hall', 'url'=>array('index')),
	array('label'=>'Manage Hall', 'url'=>array('admin')),
);
?>

<h1>Create Hall</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>