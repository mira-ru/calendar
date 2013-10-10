<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property integer $status
 * @property string $name
 * @property string $url
 * @property integer $image_id
 * @property string $desc
 * @property integer $create_time
 * @property integer $update_time
 */
class User extends CActiveRecord
{
	private $_identity;

	// Загруженный файл
	public $file;

	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;

	public static $statusNames = array(
		self::STATUS_ACTIVE => 'Активен',
		self::STATUS_DELETED => 'Удален',
	);

	const DEFAULT_IMG = '/images/nophoto_man.svg';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('image_id', 'numerical', 'integerOnly'=>true),
			array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_DELETED)),
			array('name', 'length', 'max'=>255),
			array('url', 'url', 'allowEmpty' => true,
				'message' => 'Неправильный URL страницы',
				'pattern'=>'/^(http(s?)\:\/\/)?(([0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя][0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_-]*)(\.[0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя][0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_-]*)+(\/[0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя][0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_-]*)*(\/?(\?([0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя][-0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_\[\]]*(=[-0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_\[\]\,\'\\\+%\$#]*){0,1}(&[0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя][-0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_\[\]]*(=[-0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_\[\]\,\'\\\+%\$#]*){0,1})*){0,1})?))$/i',
			),
			array('url', 'length', 'max'=>512),
			array('desc', 'length', 'max'=>5000),
			array('file', 'file', 'types'=> 'jpg, bmp, png, jpeg', 'maxFiles'=> 1, 'maxSize' => 10737418240, 'allowEmpty' => true),
			// @todo Please remove those attributes that should not be searched.
			array('id, status, name', 'safe', 'on'=>'search'),
		);
	}

	public function behaviors()
	{
		return array(
			'ModelTimeBehavior' => array(
				'class'     => 'application.components.behaviors.ModelTimeBehavior',
			),
			'CSafeContentBehavor' => array(
				'class' => 'application.components.behaviors.CSafeContentBehavior',
				'attributes' => array('desc'),
				'options' => array(
					'HTML.AllowedElements' => array(
						'em' => true,
						'a' => true,
						'strong' => true,
						'br' => true,
						'p' => true,
					),
					'HTML.AllowedAttributes' => array(
						'a.href' => true, 'a.title' => true,
					),
				),
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'status' => 'Статус',
			'name' => 'ФИО',
			'url' => 'URL страницы',
			'image_id' => 'Фото',
			'desc' => 'Описание',
			'create_time' => 'Дата создания',
			'update_time' => 'Дата обновления',
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
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('name',$this->name,true);

		if (empty($this->status)) {
			$criteria->compare('status', self::STATUS_ACTIVE);
		} else {
			$criteria->compare('status',$this->status);
		}

		$request = Yii::app()->getRequest();
		if (($dateFrom = $request->getParam('date_from'))) {
			$criteria->compare('create_time', '>=' . strtotime($dateFrom));
		}
		if (($dateTo = $request->getParam('update_to'))) {
			$criteria->compare('update_time', '<' . strtotime('+1 day', strtotime($dateTo)));
		}

		$criteria->order = 't.name ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if ($this->_identity === null) {
			$this->_identity = new UserIdentity($this->login, $this->password);
			$this->_identity->authenticate();
		}

		if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
			$duration = 3600 * 24 * 30; // 30 days
			Yii::app()->getUser()->login($this->_identity, $duration);
			return true;
		} else {
			$this->addError('password', 'Неверный пользователь или пароль');
			return false;
		}
	}

	/**
	 * Получение имени пользователя по ID
	 * @param $id
	 */
	public static function getName($id)
	{
		if (empty($id)) {
			return '';
		}
		$model = self::model()->findByPk(intval($id));
		return $model===null ? '' : $model->name;
	}

	/**
	 * Проверка на необходимость выводить линк на мастера на фронте
	 * @return bool
	 */
	public function checkShowLink()
	{
		return !empty($this->desc);
	}

}
