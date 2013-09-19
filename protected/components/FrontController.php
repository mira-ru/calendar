<?php

/**
 * @brief Базовые правила безопасности и layout для front
 */
class FrontController extends Controller
{
	/**
	 * @var array $bodyClass Дополнительные классы для тега <i>body</i>
	 */
	public $bodyClass = array();
	// Подлючаемые в загрузчик стили
	public $styles = array();
	// JS lib
	public $moduleId = array();

}
