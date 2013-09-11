<?php

/**
 * This is the model class for table "hall".
 *
 * The followings are the available columns in table 'hall':
 * @property integer $id
 * @property integer $template_id
 * @property integer $hall_id
 * @property integer $center_id
 * @property integer $service_id
 * @property integer $day_of_week
 * @property integer $status
 * @property integer $user_id
 * @property string $name
 * @property integer $start_time
 * @property integer $end_time
 */
class Event extends CActiveRecord
{
//	const STATUS_ACTIVE = 1;
//	const STATUS_DISABLED = 2;
//
//	public static $statusNames = array(
//		self::STATUS_ACTIVE => 'Активен',
//		self::STATUS_DISABLED => 'Удален',
//	);
//
//	const TYPE_SINGLE = 1;
//	const TYPE_REGULAR = 2;
//	public static $typeNames = array(
//		self::TYPE_SINGLE => 'Одиночное событие',
//		self::TYPE_REGULAR => 'Регулярное событие',
//	);

	private $_template = null;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('center_id, service_id, user_id, hall_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),

			array('user_id', 'required', 'message'=>'Укажите мастера'),
			array('name', 'required', 'message'=>'Укажите название'),
			array('service_id', 'required', 'message'=>'Укажите группу'),

			array('start_time', 'compare', 'operator'=>'>=', 'compareValue'=>7*3600, 'message'=>'некорректно указано время (с 7.00 до 21.00)'),
			array('start_time', 'compare', 'operator'=>'<=', 'compareValue'=>21*3600, 'message'=>'некорректно указано время (с 7.00 до 21.00)'),

			array('end_time', 'compare', 'operator'=>'>=', 'compareValue'=>8*3600, 'message'=>'некорректно указано время (с 8.00 до 22.00)'),
			array('end_time', 'compare', 'operator'=>'<=', 'compareValue'=>22*3600, 'message'=>'некорректно указано время (с 8.00 до 22.00)'),

			array('start_time, end_time', 'timeCheck'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, user_id, service_id, hall_id, center_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Валидатор. Проверка заданного временного интервала
	 *
	 * @param string $attribute
	 * @param array $params
	 */
	public function timeCheck($attribute, $params)
	{
		$message = 'Неверно указан временной интервал';
		if ( empty($this->start_time) || empty($this->end_time) ) {
			$this->addError('start_time' , $message);
			return false;
		}

		if ($this->start_time >= $this->end_time) {
			$this->addError('start_time' , $message);
			return false;
		}
		return true;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'service' => array(self::BELONGS_TO, 'Service', 'service_id'),
			'hall' => array(self::BELONGS_TO, 'Hall', 'hall_id'),
			'center' => array(self::BELONGS_TO, 'Center', 'center_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'center_id' => 'Центр',
			'service_id' => 'Группа услуг',
			'user_id' => 'Мастер',
			'hall_id' => 'Зал',
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
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('service_id', $this->service_id);
		$criteria->compare('hall_id', $this->hall_id);
		$criteria->compare('center_id', $this->center_id);

		$request = Yii::app()->getRequest();
		if (($dateFrom = $request->getParam('date_from'))) {
			$criteria->compare('start_time', '>=' . strtotime($dateFrom));
		}
//		if (($dateTo = $request->getParam('update_to'))) {
//			$criteria->compare('start_time', '<' . strtotime('+1 day', strtotime($dateTo)));
//		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Hall the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получение шаблона события
	 * @return EventTemplate
	 */
	public function getTemplate()
	{
		if ($this->_template !== null)
			return $this->_template;

		$this->_template = EventTemplate::model()->findByPk($this->template_id);
		return $this->_template;
	}

	/**
	 * Удаление более младших событий у данного шаблона
	 */
	public function removeYoungEvents()
	{
		if ($this->getIsNewRecord()) {
			return false;
		}
		return self::model()->deleteAllByAttributes(array('template_id'=>$this->template_id), 'id>:id', array(':id'=>$this->id));
	}
}
