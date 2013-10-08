<?php

class TestController extends AdminController
{
	public function actionTest()
	{
		$tmp = DateMap::currentWeek(time());

		FirePHP::getInstance()->fb(date("j M Y H:i:s", $tmp));
		$tmp = DateMap::nextWeek(time());
		FirePHP::getInstance()->fb(date("j M Y H:i:s", $tmp));
		die();
	}

	public function actionInfo()
	{
		phpinfo();
		die();
	}

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