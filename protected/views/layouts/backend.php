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
		<link href="/css/bootstrap.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="/css/custom/backend/offcanvas.css" rel="stylesheet">
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
						<?php
						$links = array(
							array(
								'label' => 'Мастера',
								'url' => $this->createUrl('/admin/user/index'),
								'active' => $this->id == 'user'
							),
							array(
								'label' => 'Залы',
								'url' => $this->createUrl('/admin/hall/index'),
								'active' => $this->id == 'hall'
							),
							array(
								'label' => 'Центры',
								'url' => $this->createUrl('/admin/center/index'),
								'active' => $this->id == 'center'
							),
							array(
								'label' => 'Услуги',
								'url' => $this->createUrl('/admin/service/index'),
								'active' => $this->id == 'service'
							),
						);

						foreach ($links as $link) {
							$class = $link['active'] ? 'list-group-item active' : 'list-group-item';
							echo CHtml::link($link['label'], $link['url'], array('class'=>$class));
						}

						?>
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
			<footer>Copyright &copy; <?php echo date('Y'); ?> by Miracenter. All Rights Reserved.</footer>
		</div><!--/.container-->

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="/js/lib/Jquery.js"></script>
		<script src="/js/lib/Bootstrap.js"></script>
		<script src="/js/lib/mod/backend/offcanvas.js"></script>
	</body>
</html>