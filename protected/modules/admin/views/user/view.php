<?php
/* @var $this UserController */
/**
 * @var $model User
 * @var $image ImageComponent
 */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->name,
);
$image = Yii::app()->image;

?>

<h1>Просмотр мастера #<?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'id',
			'value' => $model->id,
		),
		array(
			'name'=>'status',
			'value' => User::$statusNames[$model->status],
		),
		array(
			'name'=>'image_id',
			'type'=>'raw',
			'value'=>CHtml::image($image->getPreview($model->image_id, 'crop_150', User::DEFAULT_IMG), '', array('width'=>150, 'height'=>150))
		),
		array(
			'name'=>'name',
			'value' => $model->name,
		),
		array(
			'name'=>'url',
			'value' => $model->url,
		),
		array(
			'name'=>'desc',
			'type'=>'raw',
			'value'=>$model->desc,
		),
		array(
			'name'=> 'create_time',
			'value'=>date("d.m.Y", $model->create_time),
		),
		array(
			'name'=> 'update_time',
			'value'=>date("d.m.Y", $model->update_time),
		),
	),
)); ?>
