<?php

class WebUser extends CWebUser
{
	/**
	 * @var User
	 */
	private $_model = null;

	private $_access = array();

	/**
	 * @return integer
	 */
	public function getRole()
	{
		$model = $this->getModel();
		if ( $model )
			return $model->role;
		return null;
	}

	/**
	 * @return User
	 */
	public function getModel()
	{
		if (!$this->isGuest && $this->_model === null) {
			$this->_model = Admin::model()->findByPk((int) $this->id);
		}
		return $this->_model;
	}


	/**
	 * Метод модифицирован таким образом, что проверку доступа осуществляет по роли,
	 * которая хранится в пользовательском свойстве user->role
	 * Проверку можно осуществлять по нескольким ролям сразу. Для этого нужно первым
	 * параметром передать массив ролей. Доступ считается разрешенным, если текущий
	 * пользователь имеет хотябы одну роль, указанную в $operations
	 *
	 * @param string $operations Роль или массив ролей, которыми дложен обладать пользователь,
	 * 	чтобы получить доступ.
	 * @param array $params
	 * @param bool $allowCaching
	 * @return bool
	 */
	public function checkAccess($operations,$params=array(),$allowCaching=true)
	{
		if ( ! is_array($operations))
			$operations = array($operations);

		$valid = false;
		foreach ($operations as $operation)
		{
			if ($allowCaching && $params===array() && isset($this->_access[$operation]))
				$valid = $valid || $this->_access[$operation];
			else
				$valid = $valid || ($this->_access[$operation] = ($this->role == $operation));
		}

		return $valid;
	}

	/**
	 * Сохраняет значение в cookie
	 * @param string  $varName - ключ
	 * @param any  $value - значение
	 * @param integer|null $expire
	 */
	public function setCookieVariable($varName, $value, $expire=null)
	{
		$cookie = new CHttpCookie($varName, $value);
		$cookie->expire = (int) $expire;
		$cookie->domain = Config::getCookieDomain();

		Yii::app()->request->cookies[$varName] = $cookie;
	}

	/**
	 * Получает значение из cookie
	 * @param $varName - ключ
	 *
	 * @return any
	 */
	public function getCookieVariable($varName)
	{
		return Yii::app()->request->cookies->contains($varName) ?
			Yii::app()->request->cookies[$varName]->value : null;
	}

}