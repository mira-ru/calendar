<?php
/* @var $this HallController */
/* @var $model Hall */

$this->breadcrumbs=array(
	'Отчеты'=>array('index'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
");
?>

<h1>Отчеты</h1>


<div class="row">
	<div class="col-xs-12 col-sm-9">
		<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn btn-default')); ?>
	</div>
</div>

<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search',array(
		'model'=>$model,
		'dateTo'=>Yii::app()->request->getParam('date_to'),
		'dateFrom'=>Yii::app()->request->getParam('date_from'),
	)); ?>
</div><!-- search-form -->


<?php $this->widget('widgets.CustomGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		array(
			'name'=> 'model',
			'value'=>'Report::$models[$data->model]',
		),
		array(
			'name'=> 'model_id',
			'value'=>'$data->model_id',
		),
		array(
			'name'=> 'operation',
			'value'=>'Report::$operations[$data->operation]',
		),
		array(
			'name'=> 'field',
			'value'=>'$data->getFieldLabel()',
		),
		array(
			'name'=> 'old_value',
			'value'=>'StrUtil::getLimb($data->getOldValue(), 200)',
		),
		array(
			'name'=> 'new_value',
			'value'=>'StrUtil::getLimb($data->getNewValue(), 200)',
		),
		array(
			'name'=> 'create_time',
			'value'=>'date("d.m.Y H:i:s", $data->create_time)',
		),
	),
)); ?>
