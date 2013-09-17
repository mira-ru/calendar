<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title></title>
		<link rel="stylesheet" href="/css/bootstrap.css">
		<link rel="stylesheet" href="/css/bootstrap-theme.css">
		<?php
			foreach($styles as $style) {
				echo '<link rel="stylesheet/less" href="/css/custom/'.$style.'.less">';
			}
		?>
		<script src="/js/Lib.js"></script>
		<script>
			lib.include('Less');
			lib.include('Jquery');
			<?php
				foreach($styles as $style) {
					echo "lib.include('mod.Calendar')";
				}
			?>
		</script>
	</head>
	<body class="<?php foreach($bodyclass as $class) { echo $class.' ';} ?>">