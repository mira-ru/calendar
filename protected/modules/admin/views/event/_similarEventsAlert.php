<?php
/**
 * @var $this UserController
 * @var $template EventTemplate
 */
?>

<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title">Временной интервал события пересекается с другими событиями</h3>
	</div>
	<div class="panel-body" style="background-color: #f5f5f5">
		<?php

		if ( isset($event) )
			$similarEventsIds = array_unique(array_merge($template->similarEvents + $event->similarEvents));
		else
			$similarEventsIds = $template->similarEvents;


		$similarEvents = new CActiveDataProvider('Event', array(
			'criteria'=>array(
				'condition'=>'id in ('.implode(',', $similarEventsIds).')'
			),
			'pagination'=>array('pageSize'=>10)
		));
		$this->widget('widgets.CustomGridView', array(
			'id'=>'user-grid',
			'template'=>'{items}',
			'dataProvider'=>$similarEvents,
			'columns'=>array(
				array(
					'name'=>'id',
					'sortable' => false,
					'value' => '$data->id',
				),
				array(
					'name'=>'service_id',
					'sortable' => false,
					'type' => 'raw',
					'value' => 'empty($data->service) ? \'Услуги не указаны\' : $data->service->name',
				),
				array(
					'name'=>'hall_id',
					'sortable' => false,
					'type' => 'raw',
					'value' => 'empty($data->hall) ? \'Зал не указан\' : $data->hall->name',
				),
				array(
					'name'=>'center_id',
					'sortable' => false,
					'type' => 'raw',
					'value' => 'empty($data->center) ? \'Центр не выбран\' : $data->center->name',
				),
				array(
					'name'=>'direction_id',
					'sortable' => false,
					'type' => 'raw',
					'value' => 'empty($data->direction) ? \'Направление не выбрано\' : $data->direction->name ',
				),
				array(
					'name'=> 'start_time',
					'sortable' => false,
					'value'=>'date("d.m.Y H:i", $data->start_time)',
				),
				array(
					'name'=> 'end_time',
					'sortable' => false,
					'value'=>'date("d.m.Y H:i", $data->end_time)',
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{update}',
				),
			),
		)); ?>
	</div>

	<?php echo CHtml::activeHiddenField($template, 'forceSave', array('id'=>'forceSaveEvnt')); ?>
	<?php echo isset($event) ? CHtml::activeHiddenField($event, 'forceSave', array('id'=>'forceSaveTmpl')) : ''; ?>

	<p style="padding: 10px;">
		<button type="button" class="btn btn-danger forceSaveButton">Игнорировать предупреждение и сохранить событие с текущими настройками</button>
	</p>

	<?php Yii::app()->clientScript->registerScript('forceSave', '
		$(".forceSaveButton").click(function(){
			$("#forceSaveEvnt").val("1");
			$("#forceSaveTmpl").val("1");
			$("#event-form").submit();
		});
	', CClientScript::POS_READY);?>

</div>