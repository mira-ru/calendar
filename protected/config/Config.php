<?php
/**
 * Класс общих констант приложения
 */
class Config {
	// site errors
	static public $errors = array(
		102 => 'Документ на обработке',
		204 => 'Отсутствует содержимое',
		400 => 'Некорректный запрос',
		401 => 'Необходима авторизация',
		403 => 'Доступ запрещен',
		404 => 'Документ не найден',
		408 => 'Время ожидания истекло',
		410 => 'Документ удален',
		456 => 'Некорректируемая ошибка',
		500 => 'Внутренняя ошибка сервера',
	);

	/**
	 * Генерация css для всех цветов
	 */
	public static function generateCss()
	{
		$sql = 'SELECT color FROM service WHERE status='.Service::STATUS_ACTIVE;
		$colors = Yii::app()->db->createCommand($sql)->queryColumn();

		$content = '';
		foreach ($colors as $color) {
			$clearCode = ltrim($color, '#');
			$content .= '.c-'.$clearCode.'{background:'.$color.';}';
			$content .= '.item-'.$clearCode.':before{background:'.$color.' !important}';
			$content .= ':not(.touch) .item-'.$clearCode.':hover{color:'.$color.' !important}';
			$content .= '.item-'.$clearCode.' ul{border-color:'.$color.' !important}';
			$content .= '.item-'.$clearCode.' ul:before{background-color:'.$color.' !important}';
			$content .= '.item-'.$clearCode.' ul li:first-child{color:'.$color.' !important}';
			$content .= ':not(.touch) .item-'.$clearCode.' ul li:hover{color:'.$color.' !important}';
		}

		$sql = 'SELECT color FROM center WHERE status='.Center::STATUS_ACTIVE;
		$colors = Yii::app()->db->createCommand($sql)->queryColumn();
		foreach ($colors as $color) {
			$clearCode = ltrim($color, '#');

			$content .= '.menu-'.$clearCode;
			$content .= '.current{border-color:#'.$clearCode.' !important;}';
			$content .= '.menu-'.$clearCode;
			$content .= '.current:after{background-color:#'.$clearCode.' !important;}';
			$content .= '.menu-'.$clearCode;
			$content .= '.current a{color:#'.$clearCode.' !important;}';
		}

		$path = Yii::getPathOfAlias('application.runtime').'/assets';
		if (!file_exists($path)) {
			mkdir($path, 0700, true);
		}
		$path .= '/color.css';
		@file_put_contents($path, $content);
	}
}