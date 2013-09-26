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
		/** @var $cs EClientScript */
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerCssFile($url);
		$cs->releaseCssFile('/css/generated/calendar.css');

		?>
		<script src="/js/Lib.js"></script>
		<script>
			lib.versioninig = true;
			<?php
				foreach($this->moduleId as $module) {
					echo 'lib.include(\'mod.'.$module.'\','.$cs->getVersion().')';
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
					<div class="col-lg-7 col-sm-4 col-xs-6">Мира — центр здорового отдыха<br>Новосибирск, БЦ Речной вокзал, Добролюбова,&nbsp;2«А»<br>Телефон +7 (383) 2-300-108</div>
					<div class="col-lg-3 col-sm-5 col-xs-12 text-right">
						<a href="http://vk.com/miracentr" class="vk"></a>
						<a href="https://www.facebook.com/miracentr.ru" class="fb"></a>
						<a href="https://twitter.com/miracentr" class="tw"></a>
						<a href="http://instagram.com/miracentrru" class="ig"></a>
						<a href="http://www.youtube.com/channel/UCRXjttUgQzli-1Sh9f_zhBw" class="pl"></a>
						<a href="http://www.odnoklassniki.ru/group/51871863144642" class="ok"></a>
					</div>
				</div>
			</div>
		</div>
		<!-- EOF PAGE FOOTER -->
	</body>
</html>