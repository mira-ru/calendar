<?php
/* @var $this ServiceController */
/* @var $model Service */
/* @var $form CActiveForm */

$centerList = array(''=>'')+CHtml::listData($centers, 'id', 'name');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'service-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'class'=>'form-horizontal',
	),
)); ?>
<!--	--><?php //echo $form->errorSummary($model); ?>

	<div class="form-group <?php if ($model->hasErrors('status')) echo 'has-error'; ?>"">
		<?php echo $form->label($model,'status', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'status', Service::$statusNames, array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'status', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($model->hasErrors('center_id')) echo 'has-error'; ?>"">
		<?php echo $form->label($model,'center_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'center_id', $centerList, array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'center_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($model->hasErrors('name')) echo 'has-error'; ?>">
		<?php echo $form->label($model,'name', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
			<?php echo $form->error($model,'name', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($model->hasErrors('color')) echo 'has-error'; ?>">
		<?php echo $form->label($model,'color', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->textField($model,'color',array('size'=>60,'maxlength'=>7, 'class'=>'form-control')); ?>
			<?php echo $form->error($model,'color', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($model->hasErrors('comment')) echo 'has-error'; ?>">
		<?php echo $form->label($model, 'comment', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-8">
			<?php
			echo CHtml::activeTextArea($model, 'comment', array(
				'value'=>Kavychker::deformat($model->comment),
				'maxlength'=>5000,
				'class'=>'col-lg-12',
				'rows'=>10,
			));
			echo $form->error($model,'comment', array('class'=>'text-danger'));
			?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-default')); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->