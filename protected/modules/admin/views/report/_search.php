<?php
/* @var $form CActiveForm */
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
		<?php echo $form->label($model,'model', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'model', array(''=>'не выбрано') + Report::$models, array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'operation', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'operation', array(''=>'не выбрано') + Report::$operations, array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'model_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-2">
			<?php echo $form->textField($model, 'model_id', array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo CHtml::label('От', '', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-3">
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'htmlOptions' => array('class'=>'form-control'),
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
		<?php echo CHtml::label('До', '', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-3">
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'htmlOptions' => array('class'=>'form-control'),
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
		<div class="col-lg-offset-2 col-lg-10">
			<button type="submit" class="btn btn-default">Найти</button>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- search-form -->