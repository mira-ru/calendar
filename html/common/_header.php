<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<link rel="stylesheet" href="/css/generated/color.css">
	<?php
		foreach($styles as $style) {
			echo '<link rel="stylesheet/less" href="'.$style.'.less">';
		}
	?>

	<script src="/lib/js/vendor/less.js"></script>

</head>
<body class="<?php foreach($bodyclass as $class) { echo $class.' ';} ?>">
<div id="wrap" class="grid list-view">
	<div id="header" class="grid">
		<div class="flow align-middle">
			<div class="col-2" id="logo"><a href="http://miracentr.ru"><img src="/images/logo.png" class="_img-responsible"></a></div>
			<div class="col-3" id="name"><h1>Расписание</h1></div>
			<div class="col-5" id="search">
				<div>
					<input type="text" class="form-control ui-autocomplete-input" value="" autocomplete="off">
					<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><i></i>
				</div>
			</div>
			<div class="col-2 text-right" id="phone">
				<span>Запись по телефону:</span>
				<h2>2-300-108</h2>
			</div>
		</div>
	</div>
