<?php
/* @var $this HallController */
/* @var $model Hall */

$this->breadcrumbs=array(
	'Halls'=>array('index'),
	'Create',
);
?>

<h1>Добавление зала</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>