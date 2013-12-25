<?php

class TestController extends AdminController
{
	public function actionTest()
	{
		$tmp = strtotime(date("j M Y H:i:s", time()));
		FirePHP::getInstance()->fb(DateMap::currentDay($tmp));


		$tmp = DateMap::currentWeek(time());

		FirePHP::getInstance()->fb(date("j M Y H:i:s", $tmp));
		$tmp = DateMap::prevWeek(time());
		FirePHP::getInstance()->fb(date("j M Y H:i:s", $tmp));
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

	public function actionUrl()
	{
		$this->layout = '//layouts/empty';

//		$result = StrUtil::videoUrlConvert('http://fotki.yandex.ru/users/acerbial/album/40829/slideshow');
		$result = StrUtil::videoUrlConvert('http://youtu.be/1z4dF_tUp_c');
//		$result = StrUtil::videoUrlConvert('http://fotki.yandex.ru/users/yuricusmail-ru/album/378105/slideshow');
		$this->render('typ', array('text'=>$result));

		FirePHP::getInstance()->fb($result);
		die();
	}

	public function actionTyp()
	{
		$this->layout = '//layouts/empty';
		$direction = Direction::model()->findByPk(32);
//		$typo = new Typographus('UTF-8');
//		$text = $typo->process($direction->desc);
//		$typo = new Typograph();
//		$text = $typo->typo($direction->desc);
		$text = lib::quotes($direction->desc);

		FirePHP::getInstance()->fb($text);
//		echo $text;
		$this->render('typ', array('text'=>$text));

//		die();
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