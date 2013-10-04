<?php

class TestController extends AdminController
{
	public function actionTest()
	{
//		$path = Yii::getPathOfAlias('application.runtime').'/assets';
//		if (!file_exists($path)) {
//			mkdir($path, 0700, true);
//		}
//		$path .= '/color.css';
//
//		$content = 'kahsgdilsudfgh osaiudhgsiudfhgo OAIETHAIOUSFGHOAI UHIASUDGAOISUD GHIASUDG IALSDUG';
//
//
////		$file = fopen($path, 'W');
////		fwrite($file, $content);
////		fclose($file);
//		file_put_contents($path, $content);
//		FirePHP::getInstance()->fb($path);
		Service::generateCss();
		die();
	}

	public function actionInfo()
	{
		phpinfo();
		die();
	}


	// тестовые. для разработки с canjs
	
	public function actionIndex()
	{
		$this->layout = "//layouts/test";
		$this->render('/test/index');
	}
}