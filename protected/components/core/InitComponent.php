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
	}

        /**
         * @brief Устанавливает локаль пользователя
         */
	private function setLocale()
	{
		setlocale(LC_TIME, 'ru_RU.utf8');
	}
	
}

