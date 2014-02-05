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

	static public $routeMap = array(
		User::MODEL_TYPE => 'user',
		Service::MODEL_TYPE => 'service',
		Hall::MODEL_TYPE => 'hall',
		Direction::MODEL_TYPE => 'activity',
		Center::MODEL_TYPE => 'center',
	);

	static public $modelMap = array(
		User::MODEL_TYPE => 'User',
		Service::MODEL_TYPE => 'Service',
		Hall::MODEL_TYPE => 'Hall',
		Direction::MODEL_TYPE => 'Direction',
		Center::MODEL_TYPE => 'Center',
	);



	const VIEW_DAY = 1;
	const VIEW_MONTH = 2;
	const VIEW_WEEK = 3;
	public static $viewNames = array(
		self::VIEW_DAY => 'По дням',
		self::VIEW_WEEK => 'По неделям',
		self::VIEW_MONTH => 'По месяцам',
	);

	/**
	 * Преобразует входной объект в набор параметров для выборок
	 */
	public static function mapRequestParams($model)
	{
		$data = array(
			'centerId' => 0,
			'serviceId' => 0,
			'directionId' => 0,
			'userId' => 0,
			'hallId' => 0,
		);

		if ($model instanceof Center) {
			$data['centerId'] = $model->id;
		} elseif ($model instanceof Service) {
			$data['centerId'] = $model->center_id;
			$data['serviceId'] = $model->id;
		} elseif ($model instanceof Direction) {
			$data['centerId'] = $model->center_id;
			$data['directionId'] = $model->id;
		} elseif ($model instanceof User) {
			$data['userId'] = $model->id;
		} elseif ($model instanceof Hall) {
			$data['hallId'] = $model->id;
		} else {
			throw new CException(500);
		}
		return $data;
	}

	/**
	 * Возвращает тип представления календаря
	 * @param $model
	 * @return mixed
	 */
	public static function getViewType($model)
	{

		if (
		    !empty($_REQUEST['search'])
		    && (bool)$_REQUEST['search']
		    && $_REQUEST['search'] !== 'false'
		) { // правка для поиска
			return self::VIEW_MONTH;
		}
		$center = null;
		if ($model instanceof Direction) {
			$center = $model->center;
		} elseif ($model instanceof Service) {
			$center = $model->center;
		} elseif ($model instanceof Center) {
			$center = $model;
		}
		if ($center instanceof Center) {
			if (( $model instanceof Direction || $model instanceof User || $model instanceof Hall )) {
				return $center->detailed_view;
			} else {
				return $center->overview;
			}
		} else {
			if (( $model instanceof Direction || $model instanceof Hall )) {
				return self::VIEW_WEEK;
			} elseif ( $model instanceof User ) {
				return self::VIEW_MONTH;
			} else {
				return self::VIEW_DAY;
			}
		}
	}

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
			$content .= '.link-'.$clearCode.'{color:'.$color.' !important; text-decoration:none}';
			$content .= '.link-'.$clearCode.':hover{color:#94ce06 !important;}';
			$content .= '.item-'.$clearCode.':before{background-color:'.$color.' !important}';
			$content .= ':not(.touch) .item-'.$clearCode.':hover{color:'.$color.' !important}';
			$content .= '.item-'.$clearCode.'.expanded{color:'.$color.' !important}';
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