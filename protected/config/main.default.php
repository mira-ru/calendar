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
		'application.components.maps.DateMap',
		'application.components.lib.*',
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
		'image'=>array(
			'class'=>'ext.imgLoader.ImageComponent',
		),
		'widgetFactory'=>array(
			'widgets'=>array(
				'CJuiDatePicker'=>array(
					'options' => array(
						'firstDay' => 1,
						'dayNamesMin' => array('Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'),
						'monthNamesShort' => array('Янв','Фев','Март','Апр','Май','Июнь','Июль','Авг','Сент','Окт','Ноя', 'Дек'),
					)
				),
			),
		),
		'clientScript'=>array(
			'class'=>'application.components.core.EClientScript',
			'corePackages'=>require(dirname(__FILE__).'/../data/packages.php'),
			'coreScriptPosition'=>CClientScript::POS_HEAD,
			'timeFile'=>dirname(__FILE__).'/../runtime/timeFile.dat',
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
				array(
					'class' => 'application.components.urlRules.IndexUrlRule',
				),

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
				'css/custom/calendar.less'                  => 'css/generated/calendar.css',
			),
			'options'=>array(
				'compression'=>'yui',
				'compilerPath'=>dirname(__FILE__).'/../scripts/less/bin/lessc',
				'nodePath'=>'/usr/bin/node',
				'forceCompile'=>true,
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
		'closeMain' => true, // закрытие главной страницы
		'allowIp' => array('127.0.0.1', '195.239.212.54', '37.193.244.157', '89.189.191.1'),
		'miraPhone' => '(383) 230-01-80',
	),
), require(dirname(__FILE__) . '/main.php'));