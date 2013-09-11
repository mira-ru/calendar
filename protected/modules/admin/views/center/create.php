<?php
/* @var $this CenterController */
/* @var $model Center */

$this->breadcrumbs=array(
	'Centers'=>array('index'),
	'Create',
);
?>

<h1>Добавление центра</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>