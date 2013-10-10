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
			$this->widget('application.extensions.tinymce.ETinyMce', array(
				'model'=>$model,
				'attribute'=>'desc',
				'options'=>array(
					'theme'=>'advanced',
					'theme_advanced_buttons1' => "link, unlink, | , bold, italic, underline",
					'theme_advanced_buttons2' => "",
					'theme_advanced_buttons3' => "",
					'forced_root_block' => false,
					'force_br_newlines' => true,
					'force_p_newlines' => false,
					'height'=>'350px',
					'theme_advanced_toolbar_location'=>'top',
					'theme_advanced_toolbar_align'=> "left",
					'language'=>'ru',
					'maxLength'=>5000,
				),
			));
			echo $form->error($model,'desc', array('class'=>'text-danger'));
			?>
		</div>
	</div>

	<div class="form-group <?php if ($model->hasErrors('price')) echo 'has-error'; ?>">
		<?php echo $form->label($model, 'price', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-8">
			<?php
			$this->widget('application.extensions.tinymce.ETinyMce', array(
				'model'=>$model,
				'attribute'=>'price',
				'options'=>array(
					'theme'=>'advanced',
					'theme_advanced_buttons1' => "link, unlink, | , bold, italic, underline",
					'theme_advanced_buttons2' => "",
					'theme_advanced_buttons3' => "",
					'forced_root_block' => false,
					'force_br_newlines' => true,
					'force_p_newlines' => false,
					'height'=>'200px',
					'theme_advanced_toolbar_location'=>'top',
					'theme_advanced_toolbar_align'=> "left",
					'language'=>'ru',
					'maxLength'=>512,
				),
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