<?php
/* @var $this AdminsController */
/* @var $model Admin */

$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	'Список',
);
?>

<h1>Пользователи</h1>

<div class="row">
	<div class="col-xs-12 col-sm-9">
		<?php echo CHtml::button('Новый пользователь', array('onclick'=>'document.location = \''.$this->createUrl($this->id.'/create').'\'', 'class' => 'btn btn-primary')); ?>
	</div>
</div>

<?php $this->widget('widgets.CustomGridView', array(
	'id'=>'admin-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'id',
		'username',
		'email',
		array(
			'name'=>'role',
			'value'=>'Admin::$roles[$data->role]',
		),
		array(
			'name'=>'status',
			'value'=>'Admin::$statuses[$data->status]',
		),
		array(
			'name'=> 'create_time',
			'sortable' => false,
			'value'=>'date("d.m.Y H:i", $data->create_time)',
		),
		array(
			'name'=> 'update_time',
			'sortable' => false,
			'value'=>'date("d.m.Y H:i", $data->update_time)',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>