<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);

?>

<h1>Добавление мастера</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>