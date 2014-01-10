<?php
/* @var $this SiteController */
/* @var $model Admin */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Registration';
$this->breadcrumbs=array(
	'Registration',
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array('class'=>'form-signin')
)); ?>
	<h2 class="form-signin-heading text-center">Регистрация</h2>
	<p>Заполните все поля для регистрации на сайте:</p>

	<?php echo $form->textField($model,'username', array('class'=>'form-control', 'placeholder'=>'Имя Фамилия', 'autofocus'=>'autofocus')); ?>
	<?php echo $form->error($model,'username', array('class'=>'text-danger')); ?>

	<?php echo $form->textField($model,'email', array('class'=>'form-control', 'placeholder'=>'Адрес электронной почты')); ?>
	<?php echo $form->error($model,'email', array('class'=>'text-danger')); ?>

	<?php echo $form->passwordField($model,'password', array('class'=>'form-control', 'placeholder'=>'Пароль')); ?>
	<?php echo $form->error($model,'password', array('class'=>'text-danger')); ?>

	<?php echo CHtml::submitButton('Зарегистрироваться', array('class'=>'btn btn-lg btn-primary btn-block')); ?>

<?php $this->endWidget(); ?>
<!-- form -->
