<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	'import' => array(
		'application.commands.models.EDbConnection',
		'application.components.maps.DateMap',
		'application.components.lib.*',
	),

	// application components
	'components'=>array(
		'db'=>array(
			'class' => 'EDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=calendar',
			'emulatePrepare' => true,
			'username' => 'calendar',
			'password' => '12345',
			'charset' => 'utf8',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
		'sphinx' => array(
			'class' => 'EDbConnection',
			'connectionString' => 'mysql:host=127.0.0.1;port=9306;',
			'emulatePrepare' => true,
			'tablePrefix'=>'calendar_',
			'persistent' => true,
			'autoConnect' => false,
			'charset' => 'utf8',
			'reconnectTimeOut' => 5,
		),
	),
);