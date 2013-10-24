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
		<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter22425796 = new Ya.Metrika({id:22425796, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true, trackHash:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/22425796" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
	</body>
</html>