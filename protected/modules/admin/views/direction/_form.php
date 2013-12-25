<?php
/* @var $this DirectionController */
/* @var $model Direction */
/* @var $form CActiveForm */
/**
 * @var $image ImageComponent
 */
$image = Yii::app()->image;

$centerList = CHtml::listData($centers, 'id', 'name');
$serviceList = CHtml::listData($services, 'id', 'name');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'service-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'class'=>'form-horizontal',
		'enctype'=>'multipart/form-data',
	),
)); ?>
	<div class="form-group <?php if ($model->hasErrors('name')) echo 'has-error'; ?>">
		<?php echo $form->label($model,'name', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
			<?php echo $form->error($model,'name', array('class'=>'text-danger')); ?>
		</div>
	</div>

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
			<?php echo $form->dropDownList($model, 'center_id', $centerList,
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
			<?php echo $form->error($model,'center_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group <?php if ($model->hasErrors('service_id')) echo 'has-error'; ?>"">
		<?php echo $form->label($model,'service_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'service_id', $serviceList, array('class'=>'form-control', 'id'=>'services')); ?>
			<?php echo $form->error($model,'center_id', array('class'=>'text-danger')); ?>
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
			<?php echo CHtml::image($image->getPreview($model->image_id, 'crop_150'), '', array('width'=>150, 'height'=>150)); ?>
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

	<div class="form-group <?php if ($model->hasErrors('short_desc')) echo 'has-error'; ?>">
		<?php echo $form->label($model, 'short_desc', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-8">
			<?php
			echo CHtml::activeTextArea($model, 'short_desc', array(
				'value'=>Kavychker::deformat($model->short_desc),
				'maxlength'=>512,
				'class'=>'col-lg-12',
				'rows'=>10,
			));
			echo $form->error($model,'short_desc', array('class'=>'text-danger'));
			?>
		</div>
	</div>

	<div class="form-group <?php if ($model->hasErrors('price')) echo 'has-error'; ?>">
		<?php echo $form->label($model, 'price', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-8">
			<?php
			echo CHtml::activeTextArea($model, 'price', array(
				'value'=>Kavychker::deformat($model->price),
				'maxlength'=>2048,
				'class'=>'col-lg-12',
				'rows'=>10,
			));
			echo $form->error($model,'price', array('class'=>'text-danger'));
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