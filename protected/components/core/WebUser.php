<?php

class WebUser extends CWebUser
{
	private $_access=array();

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
				$valid = $valid || ($this->_access[$operation] = ('admin' == $operation && !empty($this->id)));
		}

		return $valid;
	}
}