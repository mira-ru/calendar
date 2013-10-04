<?php
/* @var $this CenterController */
/* @var $model Center */

$this->breadcrumbs=array(
	'Centers'=>array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
");
?>

<h1>Управление центрами</h1>

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

<?php // Подключаем скрипт для смены позиций элементов в списке
Yii::app()->clientScript->registerScriptFile('/js/lib/mod/backend/arrowsUpDown.js'); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'center-grid',
	'dataProvider'=>$model->search(),
	'itemsCssClass' => 'table table-striped',
//	'ajaxUpdate' => false,
	'selectionChanged' => 'js:function(event){
		arrowsUpDown.showArrows();
		arrowsUpDown.moveToCursor();
	}',
	'afterAjaxUpdate' => 'js:function(){
		arrowsUpDown.selectLastElement();
	}',
	'pager' => array('class'=>'application.components.widgets.CustomListPager'),
	'pagerCssClass' => '',
	'columns'=>array(
		array(
			'name'=>'id',
			'sortable' => false,
			'value' => '$data->id',
			'htmlOptions' => array("class" => 'elementId')
		),
		array(
			'name'=>'status',
			'sortable' => false,
			'value' => 'Center::$statusNames[$data->status]',
		),
		array(
			'name'=>'name',
			'sortable' => false,
			'value' => '$data->name',
		),
		array(
			'name'=>'color',
			'sortable' => false,
			'type'=>'raw',
			'value'=>'CHtml::tag(\'span\', array(\'style\'=>\'display:block; width:20px; height:20px; background:\'.$data->color.\';\'))',
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
<script type="text/javascript">
	// Инициализируем стрелки перемещения позиции
	arrowsUpDown.init("center-grid", "/admin/center/");
</script>
<style type="text/css">
	.adminArrows { position: fixed; width : 65px; height : auto; top : 50%; right : 10px; padding : 3px 3px 5px; background-color : white; border : 3px solid #999; border-radius: 8px;}
</style>

