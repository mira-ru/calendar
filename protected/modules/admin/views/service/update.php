<?php
/* @var $this ServiceController */
/* @var $model Service */

$this->breadcrumbs=array(
	'Services'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1>Редактирование услуги: <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'centers'=>$centers,)); ?>