<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
/**
 * @var $image ImageComponent
 */
$image = Yii::app()->image;
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'class'=>'form-horizontal',
		'enctype'=>'multipart/form-data',
	),
)); ?>

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

	<div class="form-group <?php if ($model->hasErrors('photo_url')) echo 'has-error'; ?>">
		<?php echo $form->label($model,'photo_url', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->textField($model,'photo_url',array('size'=>60,'maxlength'=>512, 'class'=>'form-control')); ?>
			<?php echo $form->error($model,'photo_url', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'image_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo CHtml::image($image->getPreview($model->image_id, 'crop_150', User::DEFAULT_IMG), '', array('width'=>150, 'height'=>150)); ?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-2"></div>
		<div class="col-lg-5">
			<?php
			echo CHtml::activeFileField($model, 'file');
			if ($model->hasErrors('file')) {
				echo CHtml::tag('p', array('class'=>'help-block'), $model->getError('file'));
			}
			?>
		</div>
	</div>

	<div class="form-group <?php if ($model->hasErrors('desc')) echo 'has-error'; ?>">
		<?php echo $form->label($model, 'desc', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-8">
		<?php
		echo CHtml::activeTextArea($model, 'desc', array(
			'value'=>Kavychker::deformat($model->desc),
			'maxlength'=>5000,
			'class'=>'col-lg-12',
			'rows'=>20,
		));
		echo $form->error($model,'desc', array('class'=>'text-danger'));
		?>
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
				'rows'=>15,
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