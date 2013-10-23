<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
		<?php
		Yii::app()->less->register();

		/** @var $cs EClientScript */
		$cs = Yii::app()->getClientScript();
		$cs->releaseCssFile('/css/generated/calendar.css');

		?>
	</head>
	<body class="error-page">
		<?php echo $content; ?>
	</body>
</html>