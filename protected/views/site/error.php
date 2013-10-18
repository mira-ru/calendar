<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - 404';
$this->breadcrumbs=array(
	'Error',
);
?>
<div id="wrap" class="grid ">
	<div class="flow">
		<div class="col-12 text-center">
			<div class="error-number ">
				<img class="image-responseble" src="/images/error.png" alt=""/>
				<span>404</span>
			</div>
			<h1 class="error">Документ не найден...<a href="/"><i>На главную</i> &rarr;</a></span></h1>
		</div>
	</div>
</div>