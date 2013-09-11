<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
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
	<h2 class="form-signin-heading text-center">Авторизация</h2>
	<!-- <p>Please fill out the following form with your login credentials:</p> -->

	<?php echo $form->textField($model,'username', array('class'=>'form-control', 'placeholder'=>'Адрес электронной почты', 'autofocus'=>'autofocus')); ?>
	<?php echo $form->error($model,'username', array('class'=>'text-danger')); ?>

	<?php echo $form->passwordField($model,'password', array('class'=>'form-control', 'placeholder'=>'Пароль')); ?>
	<?php echo $form->error($model,'password', array('class'=>'text-danger')); ?>

	<?php //echo $form->checkBox($model,'rememberMe'); ?>
	<?php //echo $form->label($model,'rememberMe'); ?>
	<?php //echo $form->error($model,'rememberMe'); ?>

	<?php echo CHtml::submitButton('Войти', array('class'=>'btn btn-lg btn-primary btn-block')); ?>

<?php $this->endWidget(); ?>
<!-- form -->
