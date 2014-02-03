<!DOCTYPE html>
<!--[if lt IE 9]><html class="ie"><![endif]-->
<!--[if gte IE 9]><!--><html><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>

		<noscript><meta http-equiv="refresh" content="0; URL=/badbrowser.html"></noscript>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title><?php echo $this->pageTitle; ?></title>
		<?php
		Yii::app()->less->register();

		$colorFile = Yii::getPathOfAlias('application.runtime.assets') . '/color.css';
		if (!file_exists($colorFile)) { Config::generateCss(); }
		$url = Yii::app()->getAssetManager()->publish($colorFile);
		/** @var $cs EClientScript */
		$cs = Yii::app()->getClientScript();
		$cs->registerCssFile($url);
		$cs->releaseCssFile('/css/calendar.css');
		?>
		<!--[if lt IE 9]>
			<script>
				location = '/badbrowser.html';
			</script>
		<![endif]-->
	</head>
	<body class="<?php foreach($this->bodyClass as $class) { echo $class.' ';} ?>">

		<div id="error"></div>
		<?php echo $content; ?>

		<!-- PAGE FOOTER -->
		<div id="footer" class="grid">
			<div class="flow align-middle">
				<div class="col-2"><img src="/images/src/logobottom.gif"></div>
				<div class="col-4">Мира — центр здорового отдыха<br><span>Новосибирск, БЦ Речной вокзал, </span>Добролюбова,&nbsp;2«А»<br><span>Запись по телефону </span>+7 (383) 2-300-108</div>
				<div class="col-6">
					<a href="http://vk.com/miracentr" class="vk"></a>
					<a href="https://www.facebook.com/miracentr.ru" class="fb"></a>
					<a href="https://twitter.com/miracentr" class="tw"></a>
					<a href="http://instagram.com/miracentrru" class="ig"></a>
					<a href="http://www.youtube.com/channel/UCRXjttUgQzli-1Sh9f_zhBw" class="pl"></a>
					<a href="http://www.odnoklassniki.ru/group/51871863144642" class="ok"></a>
				</div>
			</div>
		</div>
		<!-- EOF PAGE FOOTER -->
		<div id="popover"><div></div></div>
		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel" aria-hidden="true"></div>
		<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lightbox calendar-modal form">
				<div class="modal-content">
					<div class="modal-header"><button type="button" class="close btn-lg" data-dismiss="modal" aria-hidden="true">&times;</button></div>
					<div class="modal-body">
						<h4>Запись на занятие</h4>
						<div class="flow">
							<form class="col-10">
								<label class="flow">
									<strong class="col-5">Имя, Фамилия</strong>
									<input type="text" class="col-7" name="name"/>
									<div class="clearfix"></div>
								</label>
								<label class="flow">
									<strong class="col-5">Телефон</strong>
									<input type="text" class="col-7" name="phone"/>
									<div class="clearfix"></div>
								</label>
								<label class="flow">
									<strong class="col-5">E-mail</strong>
									<input type="text" class="col-7" name="phone"/>
									<div class="clearfix"></div>
								</label>
								<input type="hidden" value="Название занятия" name="service">
								<input type="submit" value="Записаться" class="-button">
							</form>
						</div>
					</div>
		</div>
		<?php Yii::app()->lmd->registerLmdFile('calendar', $cs->getVersion()); ?>
	<?php if (!YII_DEBUG) { ?><!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter22425796 = new Ya.Metrika({id:22425796, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true, trackHash:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/22425796" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter --><?php } ?>

	</body>
</html>