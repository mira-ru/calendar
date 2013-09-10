<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
		<!-- Bootstrap core CSS -->
		<link href="../../css/bootstrap.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="../../css/custom/backend/offcanvas.css" rel="stylesheet">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="../../assets/js/html5shiv.js"></script>
			<script src="../../assets/js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#"><?php echo CHtml::encode(Yii::app()->name); ?></a>
				</div>
				<div class="collapse navbar-collapse">
				<?php $this->widget('zii.widgets.CMenu', array(
					'htmlOptions'=>array(
						'class'=>'nav navbar-nav'
					),
					'items'=>array(
						array('label'=>'Home', 'url'=>array('/site/index')),
						array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
						array('label'=>'Contact', 'url'=>array('/site/contact')),
						array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
					),
				)); ?>
				</div><!-- /.nav-collapse -->
			</div><!-- /.container -->
		</div><!-- /.navbar -->
		<div class="container">
			<div class="row row-offcanvas row-offcanvas-left">
				<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
					<div class="list-group">
						<a href="#" class="list-group-item active">Link</a>
						<a href="#" class="list-group-item">Link</a>
						<a href="#" class="list-group-item">Link</a>
						<a href="#" class="list-group-item">Link</a>
						<a href="#" class="list-group-item">Link</a>
						<a href="#" class="list-group-item">Link</a>
						<a href="#" class="list-group-item">Link</a>
						<a href="#" class="list-group-item">Link</a>
						<a href="#" class="list-group-item">Link</a>
						<a href="#" class="list-group-item">Link</a>
					</div>
				</div><!--/span-->
				<div class="col-xs-12 col-sm-9">
					<p class="pull-right visible-xs">
						<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
					</p>
					<?php echo $content; ?>
				</div><!--/span-->
			</div><!--/row-->
			<hr>
			<footer>Copyright &copy; <?php echo date('Y'); ?> by Miracenter. All Rights Reserved.<br/><?php echo Yii::powered(); ?></footer>
		</div><!--/.container-->

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="../../js/lib/Jquery.js"></script>
		<script src="../../js/lib/Bootstrap.js"></script>
		<script src="../../js/lib/mod/backend/offcanvas.js"></script>
	</body>
</html>