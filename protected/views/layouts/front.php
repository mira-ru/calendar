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

		$colorFile = Yii::getPathOfAlias('application.runtime.assets') . '/color.css';
		if (!file_exists($colorFile)) { Service::generateCss(); }
		$url = Yii::app()->getAssetManager()->publish($colorFile);
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
					<div class="col-lg-7 col-sm-4 col-xs-6">Мира — центр здорового отдыха<br>Новосибирск, БЦ Речной вокзал, Добролюбова,&nbsp;2«А»<br>Запись по телефону +7 (383) 2-300-108</div>
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
 <!-- Modal -->
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Modal title</h4>
	</div>
	<div class="modal-body"></div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary">Save changes</button>
	</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
		<!-- EOF PAGE FOOTER -->
<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter22425796 = new Ya.Metrika({id:22425796, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true, trackHash:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/22425796" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
	</body>
</html>