<?php
/* @var $this DirectionController */
/* @var $model Direction */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
");
?>

<h1>Управление услугами</h1>

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
	'dateUpdate'=>Yii::app()->request->getParam('date_update'),
	'centers' => $centers,
	'services' => $services,
)); ?>
</div><!-- search-form -->

<?php $this->widget('widgets.CustomGridView', array(
	'id'=>'service-grid',
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
			'value' => 'Direction::$statusNames[$data->status]',
		),
		array(
			'name'=>'name',
			'sortable' => true,
			'value' => '$data->name',
		),
		array(
			'name'=>'service_id',
			'sortable' => false,
			'type'=>'raw',
			'value'=>'empty($data->service_id) ? \'Услуга не указана\' : $data->service->name',
		),
		array(
			'name'=>'center_id',
			'sortable'=>false,
			'type'=>'raw',
			'value'=>'empty($data->center_id) ? \'Центр не указан\' : $data->center->name',
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
