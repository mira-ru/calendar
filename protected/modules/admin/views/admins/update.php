<?php
/* @var $this AdminController */
/* @var $model Admin */

$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	'Редактирование',
);
?>

<h1>Редактирование пользователя: <?php echo $model->username; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>