<?php
/* @var $this AdminController */
/* @var $model Admin */

$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	'Создание',
);
?>

<h1>Добавление пользователя</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>