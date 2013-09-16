<?php
/**
 * @var $this UserController
 * @var $template EventTemplate
 * @var $date string
 */

$centerList = CHtml::listData($centers, 'id', 'name');
$serviceList = CHtml::listData($services, 'id', 'name');
$hallList = CHtml::listData($halls, 'id', 'name');
?>

<h1>Добавление события</h1>

<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'event-form',
		'enableAjaxValidation'=>false,
		'method' =>'post',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
	)); ?>

	<div class="form-group <?php if ($template->hasErrors('name')) echo 'has-error'; ?>">
		<?php echo $form->label($template,'name', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->textField($template,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
			<?php echo $form->error($template,'name', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('center_id')) echo 'has-error'; ?>"">
		<?php
		echo CHtml::link(
			$template->getAttributeLabel('center_id'),
			$this->createUrl('/admin/center/create'),
			array('class'=>'col-lg-2 control-label', 'target'=>'_blank')
		);
		?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($template, 'center_id', $centerList, array('class'=>'form-control')); ?>
			<?php echo $form->error($template,'center_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('service_id')) echo 'has-error'; ?>"">
		<?php
		echo CHtml::link(
			$template->getAttributeLabel('service_id'),
			$this->createUrl('/admin/service/create'),
			array('class'=>'col-lg-2 control-label', 'target'=>'_blank')
		);
		?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($template, 'service_id', $serviceList, array('class'=>'form-control')); ?>
			<?php echo $form->error($template,'service_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->getError('user_id')) echo 'has-error';?>">
		<?php
		echo CHtml::link(
			$template->getAttributeLabel('user_id'),
			$this->createUrl('/admin/user/index'),
			array('class'=>'col-lg-2 control-label', 'target'=>'_blank')
		);
		?>

		<div class="col-lg-5">
			<?php
			$this->widget('application.components.widgets.EAutoComplete', array(
				'valueName'	=> User::getName($template->user_id),
				'sourceUrl'	=> '/admin/ajax/acUser',
				'value'		=> $template->user_id,
				'options'	=> array(
					'showAnim'	=>'fold',
					'open' => 'js:function(){}',
					'minLength' => 3
				),
				'htmlOptions'	=> array('id'=>'user_id', 'name'=>'EventTemplate[user_id]', 'class' => 'form-control'),
//				'cssFile' => null,
			));
			?>
			<?php echo $form->error($template,'user_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('hall_id')) echo 'has-error'; ?>"">
		<?php
		echo CHtml::link(
			$template->getAttributeLabel('hall_id'),
			$this->createUrl('/admin/hall/create'),
			array('class'=>'col-lg-2 control-label', 'target'=>'_blank')
		);
		?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($template, 'hall_id', $hallList, array('class'=>'form-control')); ?>
			<?php echo $form->error($template,'hall_id', array('class'=>'text-danger')); ?>
		</div>
	</div>


	<div class="form-group <?php if ($template->hasErrors('day_of_week')) echo 'has-error'; ?>">
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
			<?php echo $form->error($template,'day_of_week', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('start_time')) echo 'has-error'; ?>">
		<?php echo CHtml::label('Время с', '',array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-2">
			<?php echo CHtml::textField('start_time', $startTime, array('maxlength'=>5, 'class'=>'form-control')); ?>
			<?php echo $form->error($template,'start_time', array('class'=>'text-danger')); ?>
		</div>
		<?php echo CHtml::label('по', '',array('class'=>'col-lg-1')); ?>
		<div class="col-lg-2">
			<?php echo CHtml::textField('end_time', $endTime, array('maxlength'=>5, 'class'=>'form-control')); ?>
			<?php echo $form->error($template,'end_time', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('type')) echo 'has-error'; ?>">
		<?php echo $form->label($template,'type', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($template, 'type', EventTemplate::$typeNames, array('class'=>'form-control', 'id'=>'typeSelect')); ?>
			<?php echo $form->error($template,'type', array('class'=>'text-danger')); ?>

		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<?php echo CHtml::submitButton('Создать', array('class'=>'btn btn-default')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->