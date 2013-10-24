<?php
/**
 * @var $this UserController
 * @var $template EventTemplate
 * @var $date string
 * @var $image ImageComponent
 */
$image = Yii::app()->image;

$centerList = CHtml::listData($centers, 'id', 'name');
$serviceList = CHtml::listData($services, 'id', 'name');
$hallList = array(''=>'не выбран')+CHtml::listData($halls, 'id', 'name');
$directionList = CHtml::listData($directions, 'id', 'name');

?>

<h1>Добавление события</h1>

<div class="form">

	<?php /** @var $form CActiveForm */
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'event-form',
		'enableAjaxValidation'=>false,
		'method' =>'post',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
			'enctype'=>'multipart/form-data',
		),
	)); ?>

	<div class="form-group <?php if ($template->hasErrors('center_id')) echo 'has-error'; ?>"">
		<?php echo $form->label($template, 'center_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($template, 'center_id', $centerList,
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
			<?php echo $form->error($template,'center_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('service_id')) echo 'has-error'; ?>"">
		<?php echo $form->label($template, 'service_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($template, 'service_id', $serviceList,
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
			<?php echo $form->error($template,'service_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('direction_id')) echo 'has-error'; ?>"">
		<?php echo $form->label($template, 'direction_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($template, 'direction_id', $directionList,
				array(
					'class'=>'form-control',
					'id'=>'directions',
				)
			);
			?>
			<?php echo $form->error($template,'direction_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->getError('users')) echo 'has-error';?>">
		<?php echo $form->label($template, 'users', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'',
				'sourceUrl'=>'/admin/ajax/acUser',
				'options'=>array(
					'minLength'=>'2',
					'showAnim'=>'fold',
					'select'=>'js:function(event, ui) { '
						.'var html = \'<li><a href="#">[x]</a>\'+ui.item.label+\'<input type="hidden" name="EventTemplate[users][]" value="\'+ui.item.id+\'" /></li>\'; '
						.'$("#event-users").append(html); $(this).val(""); return false; }',
				),
				'htmlOptions'=>array('class'=>'form-control', 'id'=>'event-autocomplete')
			));
			?>
			<?php echo $form->error($template,'users', array('class'=>'text-danger')); ?>
			<?php $this->renderPartial('_users', array('template'=>$template)); ?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('hall_id')) echo 'has-error'; ?>"">
		<?php echo $form->label($template, 'hall_id', array('class'=>'col-lg-2 control-label')); ?>
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

	<div class="form-group">
		<?php echo $form->label($template,'image_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo CHtml::image($image->getPreview($template->image_id, 'crop_150'), '', array('width'=>150, 'height'=>150)); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-2"></div>
		<div class="col-lg-5">
			<?php
			echo CHtml::activeFileField($template, 'file');
			if ($template->hasErrors('file')) {
				echo CHtml::tag('p', array('class'=>'help-block'), $template->getError('file'));
			}
			?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('desc')) echo 'has-error'; ?>">
		<?php echo $form->label($template, 'desc', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-8">
			<?php
			echo CHtml::activeTextArea($template, 'desc', array(
				'value'=>Kavychker::deformat($template->desc),
				'maxlength'=>5000,
				'class'=>'col-lg-12',
				'rows'=>20,
			));
			echo $form->error($template,'desc', array('class'=>'text-danger'));
			?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('comment')) echo 'has-error'; ?>">
		<?php echo $form->label($template, 'comment', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-8">
			<?php
			echo CHtml::activeTextArea($template, 'comment', array(
				'maxlength'=>5000,
				'class'=>'col-lg-12',
				'rows'=>10,
			));
			echo $form->error($template,'comment', array('class'=>'text-danger'));
			?>
		</div>
	</div>

	<div class="form-group <?php if ($template->hasErrors('is_draft')) echo 'has-error'; ?>">
		<?php echo $form->label($template,'is_draft', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($template, 'is_draft', EventTemplate::$draftNames, array('class'=>'form-control')); ?>
			<?php echo $form->error($template,'is_draft', array('class'=>'text-danger')); ?>
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