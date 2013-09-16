<?php
$yii=dirname(__FILE__).'/framework/yiilite.php';
$config=dirname(__FILE__).'/protected/config/production.php';

defined('YII_DEBUG') or define('YII_DEBUG',false);

require_once($yii);
Yii::createWebApplication($config)->run();
