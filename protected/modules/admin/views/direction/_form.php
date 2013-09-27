<?php
/* @var $this DirectionController */
/* @var $model Direction */
/* @var $form CActiveForm */

$centerList = CHtml::listData($centers, 'id', 'name');
$serviceList = CHtml::listData($services, 'id', 'name');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'service-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'class'=>'form-horizontal',
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

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-default')); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->