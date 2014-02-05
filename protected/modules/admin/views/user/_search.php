<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="wide form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'id'=>'searchForm',
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
			<button id="fileCreate" type="button" class="btn btn-success">Скачать файлом</button>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
<script type="text/javascript">
	$('#fileCreate').click(function(){
		var url = $('#searchForm').attr('action');
		$('#searchForm').attr('action', '/admin/user/csv');
		$('#searchForm').submit();
		$('#searchForm').attr('action', url)
		return false;
	});
</script>