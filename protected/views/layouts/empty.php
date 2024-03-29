<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
		<!-- Bootstrap core CSS -->
		<link href="/lib/css/bootstrap.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="/lib/css/empty.css" rel="stylesheet">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	</head>
	<body>
		<div class="container">
			<?php echo $content; ?>
		</div> <!-- /container -->
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
	</body>
</html>