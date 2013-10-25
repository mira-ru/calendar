<!DOCTYPE html>
<!--[if lt IE 9]><html class="ie"><![endif]-->
<!--[if gte IE 9]><!--><html><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>

		<noscript><meta http-equiv="refresh" content="0; URL=/badbrowser.html"></noscript>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title><?php echo $this->pageTitle; ?></title>
		<script src="http://yandex.st/jquery/2.0.3/jquery.min.js"></script>
		<script src="/jslib/modules/vendor/can.custom.js"></script>
		<!--script src="http://canjs.com/release//2.0.0/can.jquery.js"></script>
		<script src="http://canjs.com/release/latest/can.object.js"></script>
		<script src="http://canjs.com/release/latest/can.fixture.js"></script//-->
		<?php
		Yii::app()->less->register();
		?>
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<script>
				location = '/badbrowser.html';
			</script>
		<![endif]-->
	</head>
	<body class="<?php foreach($this->bodyClass as $class) { echo $class.' ';} ?>">
		<?php echo $content; ?>
	</body>
</html>