<?php
/* @var $this HallController */
/* @var $model Hall */

$this->breadcrumbs=array(
	'Halls'=>array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
");
?>

<h1>Управление залами</h1>

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
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'hall-grid',
	'dataProvider'=>$model->search(),
	'itemsCssClass' => 'table table-striped',
	'ajaxUpdate' => false,
	'pager' => array('class'=>'application.components.widgets.CustomListPager'),
	'pagerCssClass' => '',
	'columns'=>array(
		array(
			'name'=>'id',
			'sortable' => false,
			'value' => '$data->id',
		),
		array(
			'name'=>'status',
			'sortable' => false,
			'value' => 'Hall::$statusNames[$data->status]',
		),
		array(
			'name'=>'name',
			'sortable' => false,
			'value' => '$data->name',
		),
		array(
			'name'=> 'Создано',
			'sortable' => false,
			'value'=>'$data->creator',
		),
		array(
			'name'=> 'Обновлено',
			'sortable' => false,
			'value'=>'$data->updater',
		),
		array(
			'name'=> 'create_time',
			'sortable' => false,
			'value'=>'date("d.m.Y", $data->create_time)',
		),
		array(
			'name'=> 'update_time',
			'sortable' => false,
			'value'=>'date("d.m.Y", $data->update_time)',
		),
		array(
			'class'=>'CButtonColumn',
			'htmlOptions' => array('style' => 'width: 100px;')
		),
	),
)); ?>
