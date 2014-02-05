<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	const ERROR_NOT_ACTIVE = 3;

	private $_id;

	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		/**
		 * @var $record User
		 */
		$record = Admin::model()->findByAttributes(array('email'=>$this->username));

		if($record===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($record->password!==crypt($this->password,$record->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else if($record->status!=User::STATUS_ACTIVE)
			$this->errorCode=self::ERROR_NOT_ACTIVE;
		else
		{
			$this->_id=$record->id;
			$this->setState('email', $record->email);
			$this->errorCode=self::ERROR_NONE;
		}

		return !$this->errorCode;
	}

	public function getId()
	{
		return $this->_id;
	}
}