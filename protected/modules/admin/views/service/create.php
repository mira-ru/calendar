<?php
/* @var $this ServiceController */
/* @var $model Service */

$this->breadcrumbs=array(
	'Services'=>array('index'),
	'Create',
);
?>

<h1>Добавление услуги</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>