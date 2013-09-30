<?php
/**
 * @var $this EventController
 * @var $template EventTemplate
 * @var $event Event
 * @var $date string
 */

$centerList = CHtml::listData($centers, 'id', 'name');
$serviceList = CHtml::listData($services, 'id', 'name');
$hallList = CHtml::listData($halls, 'id', 'name');
$directionList = CHtml::listData($directions, 'id', 'name');
?>

<h1>Редактирование события</h1>

<div class="form">

	<?php /** @var $form CActiveForm */
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'event-form',
		'enableAjaxValidation'=>false,
		'method' =>'post',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
	)); ?>

	<div class="form-group <?php if ($event->hasErrors('center_id')) echo 'has-error'; ?>"">
		<?php
		echo CHtml::link(
			$event->getAttributeLabel('center_id'),
			$this->createUrl('/admin/center/create'),
			array('class'=>'col-lg-2 control-label', 'target'=>'_blank')
		);
		?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($event, 'center_id', $centerList,
				array(
					'class'=>'form-control',
					'prompt'=>'Выберите центр',
					'ajax' => array(
						'type'=>'POST',
						'url'=>'/admin/ajax/axService',
						'update'=>'#services',
						'data'=>array('center_id'=>'js:this.value'),
					)
				)
			); ?>
			<?php echo $form->error($event,'center_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($event->hasErrors('service_id')) echo 'has-error'; ?>"">
		<?php
		echo CHtml::link(
			$event->getAttributeLabel('service_id'),
			$this->createUrl('/admin/service/create'),
			array('class'=>'col-lg-2 control-label', 'target'=>'_blank')
		);
		?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($event, 'service_id', $serviceList,
				array(
					'class'=>'form-control',
					'id'=>'services',
					'ajax' => array(
						'type'=>'POST',
						'url'=>'/admin/ajax/axDirection',
						'update'=>'#directions',
						'data'=>array('service_id'=>'js:this.value'),
					)
				)
			);
			?>
			<?php echo $form->error($event,'service_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($event->hasErrors('direction_id')) echo 'has-error'; ?>"">
		<?php
		echo CHtml::link(
			$event->getAttributeLabel('direction_id'),
			$this->createUrl('/admin/direction/create'),
			array('class'=>'col-lg-2 control-label', 'target'=>'_blank')
		);
		?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($event, 'direction_id', $directionList,
				array(
					'class'=>'form-control',
					'id'=>'directions',
				)
			);
			?>
			<?php echo $form->error($event,'direction_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($event->getError('user_id')) echo 'has-error';?>">
		<?php
		echo CHtml::link(
			$event->getAttributeLabel('user_id'),
			$this->createUrl('/admin/user/index'),
			array('class'=>'col-lg-2 control-label', 'target'=>'_blank')
		);
		?>

		<div class="col-lg-5">
			<?php
			$this->widget('application.components.widgets.EAutoComplete', array(
				'valueName'	=> User::getName($event->user_id),
				'sourceUrl'	=> '/admin/ajax/acUser',
				'value'		=> $event->user_id,
				'options'	=> array(
					'showAnim'	=>'fold',
					'open' => 'js:function(){}',
					'minLength' => 2
				),
				'htmlOptions'	=> array('id'=>'user_id', 'name'=>'Event[user_id]', 'class' => 'form-control'),
	//				'cssFile' => null,
			));
			?>
			<?php echo $form->error($event,'user_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($event->hasErrors('hall_id')) echo 'has-error'; ?>"">
		<?php
		echo CHtml::link(
			$event->getAttributeLabel('hall_id'),
			$this->createUrl('/admin/hall/create'),
			array('class'=>'col-lg-2 control-label', 'target'=>'_blank')
		);
		?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($event, 'hall_id', $hallList, array('class'=>'form-control')); ?>
			<?php echo $form->error($event,'hall_id', array('class'=>'text-danger')); ?>
		</div>
	</div>


	<div class="form-group <?php if ($event->hasErrors('day_of_week')) echo 'has-error'; ?>">
		<label class="col-lg-2 control-label">Дата события</label>
		<div class="col-lg-5">
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'htmlOptions' => array('class'=>'form-control'),
				'name'=>'date',
				'value'=> $date,
				'options' => array(
					'autoLanguage' => false,
					'dateFormat' => 'dd.mm.yy',
					'timeFormat' => 'hh:mm',
					'changeMonth' => true,
					'changeYear' => true,
				),
			));
			?>
			<?php echo $form->error($event,'day_of_week', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($event->hasErrors('start_time')) echo 'has-error'; ?>">
		<?php echo CHtml::label('Время с', '',array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-2">
			<?php echo CHtml::textField('start_time', $startTime, array('maxlength'=>5, 'class'=>'form-control')); ?>
			<?php echo $form->error($event,'start_time', array('class'=>'text-danger')); ?>
		</div>
		<?php echo CHtml::label('по', '',array('class'=>'col-lg-1')); ?>
		<div class="col-lg-2">
			<?php echo CHtml::textField('end_time', $endTime, array('maxlength'=>5, 'class'=>'form-control')); ?>
			<?php echo $form->error($event,'end_time', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('type')) echo 'has-error'; ?>">
		<?php echo $form->label($template,'type', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($template, 'type', EventTemplate::$typeNames, array('class'=>'form-control', 'id'=>'typeSelect')); ?>
			<?php echo $form->error($template,'type', array('class'=>'text-danger')); ?>

		</div>
		<label class="checkbox-inline">
			<?php
			$htmlOptions = array('id'=>'eventCheckbox');
			if ($template->type == EventTemplate::TYPE_SINGLE) {
				$htmlOptions['disabled'] = 'disabled';
			}
			echo CHtml::checkBox('change_all', $changeAll, $htmlOptions);
			?>
			Применить ко всем событиям
		</label>
	</div>

	<script type="text/javascript">
		$('#typeSelect').change(function(){
			if (this.value==<?php echo EventTemplate::TYPE_SINGLE; ?>) {
				if (!confirm('Все подобные события в будущем будут удалены? да/нет')) {
					this.value = <?php echo EventTemplate::TYPE_REGULAR; ?>
				}
				$('#eventCheckbox').attr('disabled','disabled');
			} else {
				$('#eventCheckbox').removeAttr('disabled');
			}
		});

	</script>

	<div class="form-group <?php if ($event->hasErrors('desc')) echo 'has-error'; ?>">
		<?php echo $form->label($event,'desc', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->textArea($event, 'desc', array('maxlength'=>1024, 'class'=>'form-control', 'rows'=>10)); ?>
			<?php echo $form->error($event,'desc', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-2">
			<?php echo CHtml::submitButton('Обновить', array('class'=>'btn btn-default')); ?>
		</div>
		<div class="col-lg-2">
			<?php echo CHtml::button('Удалить',
				array('class'=>'btn btn-warning',
					'onclick'=>'if (confirm(\'Удалить это событие?\')) {document.location = \''
					.$this->createUrl($this->id.'/delete', array('id'=>$event->id)).'\';}'
				)); ?>
		</div>

		<div class="col-lg-2">
			<?php echo CHtml::button('Удалить все',
				array('class'=>'btn btn-danger',
					'onclick'=>'if (confirm(\'Удалить это и последующие события?\')) { document.location = \''
					.$this->createUrl($this->id.'/deleteall', array('id'=>$event->id))
					.'\';}'
				)); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->