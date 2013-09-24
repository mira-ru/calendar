<?php
return CMap::mergeArray(array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Mira calendar',
	'homeUrl' => 'http://calendar.local',
	'id' => 'calendar',

	// preloading 'log' component
	'preload'=>array('log', 'init'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.config.Config',
		'ext.yii-firephp.*',
	),

	'modules'=>array(
		'admin',
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'trololo',
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(
		'clientScript'=>array(
			'class'=>'CClientScript',
			'corePackages'=>require(dirname(__FILE__).'/../data/packages.php'),
			'coreScriptPosition'=>CClientScript::POS_HEAD,
		),
		'user' => array(
			'allowAutoLogin' => true,
			'loginUrl' => array('site/login'),
			'class' => 'application.components.core.WebUser',
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'              => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
			),
		),

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=calendar',
			'emulatePrepare' => true,
			'username' => 'calendar',
			'password' => '12345',
			'charset' => 'utf8',
			'enableProfiling' => true,
			'enableParamLogging' => true,
		),
		'less'=>array(
			'class'=>'ext.yii-less.components.Less',
			'mode'=>'server',
			'files'=>array(
				//'css/custom/calendar.less'                  => 'css/generated/calendar.css',
				'css/custom/test.less'                  => 'css/generated/test.css',
			),
			'options'=>array(
				'compression'=>'yui',
				'compilerPath'=>'/usr/bin/lessc',
				'nodePath'=>'/usr/bin/node',
				'forceCompile'=>true,
				'rootPath'=>'/var/www/calendar/css',
			),
		),
		'init'=>array(
			'class'=>'application.components.core.InitComponent'
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				array(
					'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
					'ipFilters' => array('127.0.0.1', '127.0.1.1'),
				),
			),
		),
	),

	'params'=>array(
		'users' => array('admin'=>'123qwe'),
		'adminEmail'=>'webmaster@example.com',
	),
), require(dirname(__FILE__) . '/main.php'));