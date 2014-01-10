<?php

/**
 * This is the model class for table "admin".
 *
 * The followings are the available columns in table 'admin':
 * @property integer $id
 * @property integer $status
 * @property integer $role
 * @property string $email
 * @property string $username
 * @property string $password
 * @property integer $create_time
 * @property integer $update_time
 */
class Admin extends CActiveRecord
{
	/*
	 * Статусы пользователей
	 */
	const STATUS_ACTIVE = 1;
	const STATUS_MODERATE = 2;
	const STATUS_DISABLED = 3;
	const STATUS_DELETED = 4;

	static public $statuses = array(
		self::STATUS_ACTIVE => 'Активен',
		self::STATUS_MODERATE => 'На модерации',
		self::STATUS_DISABLED => 'Отключен',
		self::STATUS_DELETED => 'Удален',
	);


	/*
	 * Роли пользователей
	 */
	const ROLE_POWERADMIN = 1;
	const ROLE_ADMIN = 2;

	static public $roles = array(
		self::ROLE_POWERADMIN => 'Главный администратор',
		self::ROLE_ADMIN => 'Администратор',
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email, status, role', 'required'),

			array('username', 'length', 'min' => 2, 'max'=>255),
			array('password', 'length', 'min' => 4, 'max'=>255),
			array('password', 'filter', 'filter'=>array($this,'cryptPassword'), 'on'=>'register'),

			array('email', 'email'),
			array('email', 'unique'),

			array('id, status, role, email, username, password, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	public function behaviors(){
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => 'update_time',
				'setUpdateOnCreate' => true,
			)
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Имя',
			'email' => 'Email',
			'password' => 'Пароль',
			'status' => 'Статус',
			'role' => 'Роль',
			'create_time' => 'Время создания',
			'update_time' => 'Время обновления',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('status',$this->status);
		$criteria->compare('role',$this->role);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Admin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Шифрование пароля
	 */
	public function cryptPassword($value)
	{
		return crypt($value, $value);
	}

	public function register($role, $status)
	{
		if ( !$this->getIsNewRecord() )
			return false;

		$this->setScenario('register');
		$this->role = $role;
		$this->status = $status;

		if ( $this->validate() )
			return $this->save(false);

		return false;
	}
}
