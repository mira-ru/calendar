<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
");


/*
 * TODO: проверить striped и пагинатор, мб подменить
 */
?>

<h1>Управление мастерами</h1>

<div class="row">
	<div class="col-xs-12 col-sm-9">
		<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn btn-default')); ?>
		<?php echo CHtml::button('Добавить', array('onclick'=>'document.location = \''.$this->createUrl($this->id.'/create').'\'', 'class' => 'btn btn-primary')); ?>
	</div>
</div>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
	'dateTo'=>Yii::app()->request->getParam('date_to'),
	'dateFrom'=>Yii::app()->request->getParam('date_from'),
	'dateUpdate'=>Yii::app()->request->getParam('date_update')
)); ?>
</div><!-- search-form -->

<?php $this->widget('widgets.CustomGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		array(
			'name'=>'id',
			'sortable' => false,
			'value' => '$data->id',
		),
		array(
			'name'=>'status',
			'sortable' => false,
			'value' => 'User::$statusNames[$data->status]',
		),
		array(
			'name'=>'name',
			'sortable' => true,
			'value' => '$data->name',
		),
		array(
			'name'=> 'create_time',
			'sortable' => false,
			'value'=>'date("d.m.Y", $data->create_time)',
		),
		array(
			'name'=> 'update_time',
			'sortable' => true,
			'value'=>'date("d.m.Y", $data->update_time)',
		),
		array(
			'name'=>'desc',
			'sortable'=>false,
			'value'=>'empty($data->desc) ? "Нет" : "Есть"',
		),
		array(
			'name'=>'url',
			'sortable'=>false,
			'value'=>'empty($data->url) ? "Нет" : "Есть"',
		),
		array(
			'name'=>'photo_url',
			'sortable'=>false,
			'value'=>'empty($data->photo_url) ? "Нет" : "Есть"',
		),
		array(
			'class'=>'CButtonColumn',
			'htmlOptions' => array('style' => 'width: 100px;')
		),
	),
)); ?>
