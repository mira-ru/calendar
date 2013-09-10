<?php
/* @var $this HallController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Halls',
);

$this->menu=array(
	array('label'=>'Create Hall', 'url'=>array('create')),
	array('label'=>'Manage Hall', 'url'=>array('admin')),
);
?>

<h1>Halls</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
