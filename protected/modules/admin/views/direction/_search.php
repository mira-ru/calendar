<?php
/* @var $this DirectionController */
/* @var $model Direction */
/* @var $form CActiveForm */
$centerList = array(''=>'Все')+CHtml::listData($centers, 'id', 'name');
$serviceList = array(''=>'Все')+CHtml::listData($services, 'id', 'name');
?>
<hr>
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array(
		'class'=>'form-horizontal',
	),
)); ?>
	<div class="form-group">
		<?php echo $form->label($model,'id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->textField($model,'id', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'status', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'status', User::$statusNames, array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'name', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'center_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'center_id', $centerList, array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'service_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'service_id', $serviceList, array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo CHtml::label('Время от', '', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'htmlOptions' => array('class'=>'span5'),
				'name'=>'date_from',
				'value'=> $dateFrom,
				'options' => array(
					'autoLanguage' => false,
					'dateFormat' => 'dd.mm.yy',
					'timeFormat' => 'hh:mm',
					'changeMonth' => true,
					'changeYear' => true,
				),
			));
			?>
		</div>
	</div>

	<div class="form-group">
		<?php echo CHtml::label('Время до', '', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'htmlOptions' => array('class'=>'span5'),
				'name'=>'date_to',
				'value'=> $dateTo,
				'options' => array(
					'autoLanguage' => false,
					'dateFormat' => 'dd.mm.yy',
					'timeFormat' => 'hh:mm',
					'changeMonth' => true,
					'changeYear' => true,
				),
			));
			?>
		</div>
	</div>

	<div class="form-group">
		<?php echo CHtml::label('Дата обновления', '', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'htmlOptions' => array('class'=>'span5'),
				'name'=>'date_update',
				'value'=> $dateUpdate,
				'options' => array(
					'autoLanguage' => false,
					'dateFormat' => 'dd.mm.yy',
					'timeFormat' => 'hh:mm',
					'changeMonth' => true,
					'changeYear' => true,
				),
			));
			?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'desc', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php
			echo CHtml::dropDownList('check_desc',
				Yii::app()->request->getParam('check_desc'),
				array(''=>'', 1 => 'Есть описание', 2 => 'Нет описания'), array('class'=>'form-control')
			);
			?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'url', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php
			echo CHtml::dropDownList('check_video',
				Yii::app()->request->getParam('check_video'),
				array(''=>'', 1 => 'Есть видео', 2 => 'Нет видео'), array('class'=>'form-control')
			);
			?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'photo_url', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php
			echo CHtml::dropDownList('check_photo',
				Yii::app()->request->getParam('check_photo'),
				array(''=>'', 1 => 'Есть фото', 2 => 'Нет фото'), array('class'=>'form-control')
			);
			?>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Найти</button>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- search-form -->
<hr>