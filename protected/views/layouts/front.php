<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo $this->pageTitle; ?></title>
		<link rel="stylesheet" href="/css/bootstrap.css">
		<link rel="stylesheet" href="/css/bootstrap-theme.css">
		<?php
		Yii::app()->less->register();
		$url = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.runtime.assets') . '/color.css');
		/** @var $cs CClientScript */
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerCssFile($url);
		$cs->registerCssFile('/css/generated/calendar.css')

		?>
		<script src="/js/Lib.js"></script>
		<script>
			<?php
				foreach($this->moduleId as $module) {
					echo "lib.include('mod.".$module."')";
				}
			?>
		</script>
	</head>
	<body class="<?php foreach($this->bodyClass as $class) { echo $class.' ';} ?>">

		<?php echo $content; ?>

		<!-- PAGE FOOTER -->
		<div id="footer">
			<div class="container">
				<div class="row">
					<div class="col-lg-2 col-sm-3 col-xs-6"><img src="/images/src/logobottom.gif"></div>
					<div class="col-lg-7 col-sm-4 col-xs-6">Мира — центр здорового отдыха<br>Новосибирск, БЦ Речной вокзал, Добролюбова, 2«А»</div>
					<div class="col-lg-3 col-sm-5 col-xs-12 text-right">
						<a href="#" class="vk"></a>
						<a href="#" class="fb"></a>
						<a href="#" class="tw"></a>
						<a href="#" class="ig"></a>
						<a href="#" class="pl"></a>
						<a href="#" class="ok"></a>
					</div>
				</div>
			</div>
		</div>
		<!-- EOF PAGE FOOTER -->
	</body>
</html>