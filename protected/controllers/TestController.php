<?php

class TestController extends AdminController
{
	public function actionTest()
	{
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

	public function actionUrl()
	{
		FirePHP::getInstance()->fb($this->createUrl('test', array('lol[test]'=>1)));

		die();
	}

	public function actionImage()
	{
		$src = 'images/break.png';
		/** @var $image ImageComponent */
		$image = Yii::app()->image;
//		$id = $image->putImage($src, 'trololo', 'sdjfgasdgbfisdbfg');
//
//		$preview = $image->getPreview($id, 'crop_200');

//		FirePHP::getInstance()->fb($preview);
//		$image->deleteOrigin(6);
//		$image->deleteOrigin(5);
//		$image->deleteOrigin(7);
	}
}