<?php

/**
 * @brief Атоподгружаемый класс, выполняет инициализацию параметров
 */
class InitComponent extends CComponent
{
        public $enable = true;
        
        public function init()
	{
		Yii::setPathOfAlias('widgets', dirname(__FILE__).'/../widgets');
                if(!$this->enable)
                        return false;

		$this->setLocale();
		$this->close();
	}

        /**
         * @brief Устанавливает локаль пользователя
         */
	private function setLocale()
	{
		setlocale(LC_TIME, 'ru_RU.utf8');
		mb_regex_encoding ('utf-8');
		date_default_timezone_set('Asia/Novosibirsk');
	}

	/**
	 * Закрытие определенных страниц
	 */
	private function close()
	{
		if (empty(Yii::app()->params->closeMain))
			return true;

		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();
		$uri = $request->getRequestUri();

		$allowUrl = array('/admin', '/site/login', '/site/logout');
		foreach ($allowUrl as $url) {
			if (!is_bool( strpos($uri, $url) ) ) {
				return true;
			}
		}

		$allowIp = empty(Yii::app()->params->allowIp) || !is_array(Yii::app()->params->allowIp) ? array() : Yii::app()->params->allowIp;
		$ip = Yii::app()->request->userHostAddress;

		if (in_array($ip, $allowIp)) {
			return true;
		}

		$url = 'http://vk.com/photo-50244984_311296741';
		Yii::app()->getRequest()->redirect($url,false,302);
		die();
	}
}

