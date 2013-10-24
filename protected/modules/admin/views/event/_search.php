<?php
/* @var $this EventController */
/* @var $model Event */
/* @var $form CActiveForm */

$centerList = array(''=>'Все')+CHtml::listData($centers, 'id', 'name');
$serviceList = array(''=>'Все')+CHtml::listData($services, 'id', 'name');
$hallList = array(''=>'Все')+CHtml::listData($halls, 'id', 'name');

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
		<?php echo $form->label($model,'hall_id', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'hall_id', $hallList, array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'event_type', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'event_type', array(''=>'Все')+EventTemplate::$typeNames, array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'is_draft', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
			<?php echo $form->dropDownList($model, 'is_draft', array(''=>'Все')+EventTemplate::$draftNames, array('class'=>'form-control')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model, 'users', array('class'=>'col-lg-2 control-label')); ?>

		<div class="col-lg-5">
			<?php
			$this->widget('application.components.widgets.EAutoComplete', array(
				'valueName'	=> User::getName($model->user_id),
				'sourceUrl'	=> '/admin/ajax/acUser',
				'value'		=> $model->user_id,
				'options'	=> array(
					'showAnim'	=>'fold',
					'open' => 'js:function(){}',
					'minLength' => 3
				),
				'htmlOptions'	=> array('id'=>'user_id', 'name'=>'Event[user_id]', 'class' => 'form-control'),
				//				'cssFile' => null,
			));
			?>
			<?php echo $form->error($model,'user_id', array('class'=>'text-danger')); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->label($model,'start_time', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
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
		<?php echo $form->label($model,'end_time', array('class'=>'col-lg-2 control-label')); ?>
		<div class="col-lg-5">
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
<hr>