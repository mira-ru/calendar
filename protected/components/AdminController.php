<?php

/**
 * @brief Базовые правила безопасности и layout для Панели администрирования
 */
class AdminController extends Controller
{
        /**
         * @brief Указывает на layout для панели администрирования
         * @var string
         */
	public $layout = '//layouts/backend';

	public function filters() {
		return array('accessControl');
	}

        /**
         * @return array
         */
	public function accessRules() {

		return array(
			array('allow',
				'roles'=>array(
					'admin', 'alexandrovna13'
				),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
}
