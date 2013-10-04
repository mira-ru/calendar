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
	'dateFrom'=>Yii::app()->request->getParam('date_from'),
	'dateTo'=>Yii::app()->request->getParam('date_to'),
	'centers' => $centers,
	'services' => $services,
	'halls' => $halls,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
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
			'name'=>'user_id',
			'sortable' => false,
			'type' => 'raw',
			'value' => 'empty($data->user) ? \'Мастер не указан\' : CHtml::link($data->user->name, Yii::app()->controller->createUrl(\'/admin/event/index\', array(\'Event[user_id]\'=> $data->user->id) ))',
		),
		array(
			'name'=>'service_id',
			'sortable' => false,
			'type' => 'raw',
			'value' => 'empty($data->service) ? \'Услуги не указаны\' : CHtml::link($data->service->name, Yii::app()->controller->createUrl(\'/admin/event/index\', array(\'Event[service_id]\'=> $data->service->id) ))',
		),
		array(
			'name'=>'hall_id',
			'sortable' => false,
			'type' => 'raw',
			'value' => 'empty($data->hall) ? \'Зал не указан\' : CHtml::link($data->hall->name, Yii::app()->controller->createUrl(\'/admin/event/index\', array(\'Event[hall_id]\'=> $data->hall->id) ))',
		),
		array(
			'name'=>'center_id',
			'sortable' => false,
			'type' => 'raw',
			'value' => 'empty($data->center) ? \'Центр не выбран\' : CHtml::link($data->center->name, Yii::app()->controller->createUrl(\'/admin/event/index\', array(\'Event[center_id]\'=> $data->center->id) ))',
		),
		array(
			'name'=>'direction_id',
			'sortable' => false,
			'type' => 'raw',
			'value' => 'empty($data->direction) ? \'Направление не выбрано\' : CHtml::link($data->direction->name, Yii::app()->controller->createUrl(\'/admin/event/index\', array(\'Event[direction_id]\'=> $data->direction->id) ))',
		),
		array(
			'name'=>'day_of_week',
			'sortable' => false,
			'value' => 'DateMap::$smallDayMap[$data->day_of_week]',
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
			'template'=>'{update}{delete}',
		),
	),
)); ?>
