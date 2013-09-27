<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'class'=>'form-horizontal',
	),
)); ?>

<!--	--><?php //echo $form->errorSummary($model); ?>

	<div class="form-group <?php if ($model->hasErrors('status')) echo 'has-error'; ?>"">
		<?php echo $form->label($model,'status', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'status', User::$statusNames, array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'status', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($model->hasErrors('name')) echo 'has-error'; ?>">
		<?php echo $form->label($model,'name', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
			<?php echo $form->error($model,'name', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($model->hasErrors('url')) echo 'has-error'; ?>">
		<?php echo $form->label($model,'url', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>512, 'class'=>'form-control')); ?>
			<?php echo $form->error($model,'url', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-default')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->