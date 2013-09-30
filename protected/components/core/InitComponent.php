<?php

/**
 * @brief Атоподгружаемый класс, выполняет инициализацию параметров
 */
class InitComponent extends CComponent
{
        public $enable = true;
        
        public function init()
	{
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
	}

	/**
	 * Закрытие определенных страниц
	 */
	private function close()
	{
		if (empty(Yii::app()->params->closeMain))
			return true;

		$allowIp = array('127.0.0.1', '195.239.212.54');
		$ip = Yii::app()->request->userHostAddress;

		if (in_array($ip, $allowIp)) {
			return true;
		}

		$url = 'http://vk.com/photo-50244984_311296741';
		Yii::app()->getRequest()->redirect($url,false,302);
		die();
	}
}

