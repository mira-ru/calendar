<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<script type="text/javascript" src="/lib/js/vendor/jquery.js"></script>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>

		<?php
		/** @var $cs CClientScript */
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile('/lib/js/vendor/jquery.cookie.js', CClientScript::POS_HEAD);
		$cs->registerScriptFile('/lib/js/vendor/bootstrap/Bootstrap.js');

		$cs->registerScriptFile('/lib/js/backend/offcanvas.js', CClientScript::POS_END);
		$cs->registerCssFile('/lib/css/bootstrap.css');
		$cs->registerCssFile('/lib/css/offcanvas.css');
		?>
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
					<a class="navbar-brand" href="/admin"><?php echo CHtml::encode(Yii::app()->name); ?></a>
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
								'active' => $this->id == 'user',
								'visible' => Yii::app()->user->checkAccess(array(
									    Admin::ROLE_POWERADMIN,
									    Admin::ROLE_ADMIN,
								    )),
							),
							array(
								'label' => 'Залы',
								'url' => $this->createUrl('/admin/hall/index'),
								'active' => $this->id == 'hall',
								'visible' => Yii::app()->user->checkAccess(array(
									    Admin::ROLE_POWERADMIN,
									    Admin::ROLE_ADMIN,
								    )),
							),
							array(
								'label' => 'Центры',
								'url' => $this->createUrl('/admin/center/index'),
								'active' => $this->id == 'center',
								'visible' => Yii::app()->user->checkAccess(array(
									    Admin::ROLE_POWERADMIN,
									    Admin::ROLE_ADMIN,
								    )),
							),
							array(
								'label' => 'Услуги',
								'url' => $this->createUrl('/admin/service/index'),
								'active' => $this->id == 'service',
								'visible' => Yii::app()->user->checkAccess(array(
									    Admin::ROLE_POWERADMIN,
									    Admin::ROLE_ADMIN,
								    )),
							),
							array(
								'label' => 'Направления',
								'url' => $this->createUrl('/admin/direction/index'),
								'active' => $this->id == 'direction',
								'visible' => Yii::app()->user->checkAccess(array(
									    Admin::ROLE_POWERADMIN,
									    Admin::ROLE_ADMIN,
								    )),
							),
							array(
								'label' => 'События',
								'url' => $this->createUrl('/admin/event/index'),
								'active' => $this->id == 'event'
							),
							array(
								'label' => 'Отчеты',
								'url' => $this->createUrl('/admin/report/index'),
								'active' => $this->id == 'report',
								'visible' => Yii::app()->user->checkAccess(array(
									    Admin::ROLE_POWERADMIN,
									    Admin::ROLE_ADMIN,
								    )),
							),
							array(
								'label' => 'Пользователи',
								'url' => $this->createUrl('/admin/admins/index'),
								'active' => $this->id == 'admins',
								'visible' => Yii::app()->user->checkAccess(Admin::ROLE_POWERADMIN),
							),
						);

						foreach ($links as $link) {
							if ( isset($link['visible']) && !$link['visible'] ) continue;

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
	</body>
</html>