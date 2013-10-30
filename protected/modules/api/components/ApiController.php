<?php

abstract class ApiController extends CController
{
	abstract public function actionList();
	abstract public function actionView($id);
	abstract public function actionCreate();
	abstract public function actionUpdate($id);
	abstract public function actionDelete($id);


	/**
	 * Генерирует критерию на основе переданных параметров
	 * @param array $params - параметры критерии (limit, offset, sort, filter)
	 * Параметр sort имеет формат JSON [{"property":"title", "direction":"DESC"}, {"property":"create_time", "direction":"ASC"}]
	 * Параметр filter имеет формат JSON [{"property":"status", "value":"1", "operator":">"}]. Допустимые операторы: '=', '<>', '>', '<', '<=', '>='
	 * Параметры limit и offset - целые числа
	 * @return CDbCriteria
	 * @throws CHttpException
	 */
	public function genCriteria($params=null)
	{
		if ( !$params )
			$params = $_GET;

		$criteria = new CDbCriteria();

		$criteria->limit = isset($params['limit'])
			? intval($params['limit']) : 100;

		$criteria->offset = isset($params['offset'])
			? intval($params['offset']) : 0;

		// формирует условия для сортировки
		if ( isset($params['sort']) ) {

			$sortParams = CJSON::decode($params['sort']);
			if ( !is_array($sortParams) ) $this->response(400, "Wrong JSON format for 'sort' parameter");

			$sortConditions = array();
			foreach ($sortParams as $sp) {
				if ( !isset($sp['property']) || !isset($sp['direction']) )
					$this->response(400, "Wrong item format for 'sort' parameter. Invalid 'property' or 'direction'.");

				$sortConditions[] = $sp['property'] . ' ' . $sp['direction'];
			}

			$criteria->order = implode(',', $sortConditions);
		}

		// формирует условия для фильтрации
		if ( isset($params['filter']) ) {

			$filterParams = CJSON::decode($params['filter']);
			if ( !is_array($filterParams) ) $this->response(400, "Wrong JSON format for 'filter' parameter");

			foreach ($filterParams as $fp) {

				if ( !isset($fp['property']) || !isset($fp['value']) || !isset($fp['operator']))
					$this->response(400, "Wrong item format for 'filter' parameter. Invalid 'property' or 'value' or 'operator'.");

				$property = $fp['property'];
				$operator = $fp['operator'];
				$value = $fp['value'];

				if ( !in_array($operator, array('=', '<>', '>', '<', '<=', '>=')) )
					$this->response(400, "Wrong item format for 'filter' parameter. Invalid 'operator' value.");

				$criteria->compare($property, "$operator$value");
			}
		}

		return $criteria;
	}


	/**
	 * Отправка ответа клиенту
	 * @param int $status - статус-код ответа
	 * @param string $body - отправляемый контент
	 * @param string $content_type
	 */
	public function response($status = 200, $body = '', $content_type = 'application/json')
	{
		ob_clean();
		header('HTTP/1.1 ' . $status . ' ' . self::getStatusCodeMessage($status));
		header('Content-type: ' . $content_type);

		if($body != '')
			echo $body;
		else
			echo CJSON::encode(array(
				'success' => false,
				'message' => self::getStatusCodeMessage($status),
				'data' => array(
					'code' => $status,
					'message' => self::getStatusCodeMessage($status),
				),
			));

		Yii::app()->end();
	}


	/**
	 * Возвращает текстовое описание HTTP кода ответа
	 * @param $status
	 * @return string
	 */
	static public function getStatusCodeMessage($status)
	{
		$codes = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
		);
		return (isset($codes[$status])) ? $codes[$status] : '';
	}

}
