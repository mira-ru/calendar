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
			'name'=> 'Тип',
			'value'=>'Report::$models[$data->model]',
		),
		array(
			'name'=> 'Название/ID',
			'value'=>'$data->getModelName()',
		),
		array(
			'name'=> 'Действие',
			'value'=>'Report::$operations[$data->operation]',
		),
		array(
			'name'=> 'Поле',
			'value'=>'$data->getFieldLabel()',
		),
		array(
			'name'=> 'Было',
			'value'=>'StrUtil::getLimb($data->getHumanVal($data->old_value), 200)',
		),
		array(
			'name'=> 'Стало',
			'value'=>'StrUtil::getLimb($data->getHumanVal($data->new_value), 200)',
		),
		array(
			'name'=> 'Время',
			'value'=>'date("d.m.Y H:i:s", $data->create_time)',
		),
		array(
			'name'=> 'Пользователь',
			'value'=>'$data->getUserById()',
		),
	),
)); ?>
