<?php

/**
 * This is the model class for table "sign_up".
 *
 * The followings are the available columns in table 'sign_up':
 * @property integer $id
 * @property integer $eventId
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property integer $create_time
 */
class SignUp extends CActiveRecord
{

	public function tableName()
	{
		return 'sign_up';
	}

	public function rules()
	{
		return array(
			array('name, phone, eventId, create_time', 'required'),
			array('eventId, create_time', 'numerical', 'integerOnly'=>true),
			array('name, phone, email', 'length', 'max'=>255),
			array('id, name, phone, email, eventId, create_time', 'safe', 'on'=>'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Имя, Фамилия',
			'phone' => 'Телефон',
			'email' => 'Email',
			'eventId' => 'ID события',
			'create_time' => 'Дата записи',
		);
	}


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
