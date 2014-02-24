<?php

class UserLogBehavior extends CActiveRecordBehavior
{
	public $nameCreatorId = 'creator_id';
	public $nameUpdaterId = 'updater_id';

	function __construct()
	{
	}

	public function beforeSave($event)
	{
		if ( !Yii::app() instanceof CWebApplication )
			return true;

		$owner = $this->getOwner();

		if ( Yii::app()->user->isGuest )
			throw new CHttpException(500, 'Сохранять модель могут только авторизованные пользователи');

		$currentUserId = Yii::app()->user->id;

		if ($owner->getIsNewRecord())
			$owner->{$this->nameCreatorId} = $owner->{$this->nameUpdaterId} = $currentUserId;
		else
			$owner->{$this->nameUpdaterId} = $currentUserId;
	}

	public function getCreator()
	{
		$owner = $this->getOwner();

		$admin = Admin::model()->findByPk($owner->{$this->nameCreatorId});

		if ( $admin )
			return $admin->username;

		return '';
	}

	public function getUpdater()
	{
		$owner = $this->getOwner();

		$admin = Admin::model()->findByPk($owner->{$this->nameUpdaterId});

		if ( $admin )
			return $admin->username;

		return '';
	}

}
