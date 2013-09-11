<?php
/* @var $this EventController */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");


/*
 * TODO: проверить striped и пагинатор, мб подменить
 */
?>

<h1>Управление событиями</h1>

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
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'itemsCssClass' => 'table table-striped',
	'columns'=>array(
		array(
			'name'=>'id',
			'sortable' => false,
			'value' => '$data->id',
		),
		array(
			'name'=>'name',
			'sortable' => false,
			'value' => '$data->name',
		),
		array(
			'name'=>'user_id',
			'sortable' => false,
			'value' => '$data->user->name',
		),
		array(
			'name'=>'service_id',
			'sortable' => false,
			'value' => '$data->service->name',
		),
		array(
			'name'=>'hall_id',
			'sortable' => false,
			'value' => '$data->hall->name',
		),
		array(
			'name'=>'center_id',
			'sortable' => false,
			'value' => '$data->center->name',
		),
		array(
			'name'=> 'start_time',
			'sortable' => false,
			'value'=>'date("d.m.Y H:i:s", $data->start_time)',
		),
		array(
			'name'=> 'end_time',
			'sortable' => false,
			'value'=>'date("d.m.Y H:i:s", $data->end_time)',
		),
		array(
			'class'=>'CButtonColumn',
//			'htmlOptions' => array('style' => 'width: 30px;'),
			'template'=>'{update}{delete}',
		),
	),
)); ?>
