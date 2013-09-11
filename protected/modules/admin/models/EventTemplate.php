<?php

/**
 * This is the model class for table "event_template".
 *
 * The followings are the available columns in table 'hall':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $type
 * @property integer $status
 * @property integer $user_id
 * @property integer $hall_id
 * @property integer $center_id
 * @property integer $service_id
 * @property string $name
 * @property integer $day_of_week
 * @property integer $init_time - день первого события(timestamp)
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $create_time
 * @property integer $update_time
 */
class EventTemplate extends CActiveRecord
{
//	public $eventTime = null;


	const STATUS_ACTIVE = 1;
	const STATUS_DISABLED = 2;

	public static $statusNames = array(
		self::STATUS_ACTIVE => 'Активен',
		self::STATUS_DISABLED => 'Удален',
	);

	const TYPE_SINGLE = 1;
	const TYPE_REGULAR = 2;
	public static $typeNames = array(
		self::TYPE_SINGLE => 'Одиночное событие',
		self::TYPE_REGULAR => 'Регулярное событие',
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'event_template';
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
			array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_DISABLED)),
			array('type', 'in', 'range'=>array(self::TYPE_SINGLE, self::TYPE_REGULAR)),
			array('name', 'length', 'max'=>255),
			array('user_id', 'required', 'message'=>'Укажите мастера'),
			array('name', 'required', 'message'=>'Укажите название'),
			array('service_id', 'required', 'message'=>'Укажите группу'),
			array('center_id', 'required', 'message'=>'Укажите центр'),

			array('start_time', 'compare', 'operator'=>'>=', 'compareValue'=>7*3600, 'message'=>'некорректно указано время (с 7.00 до 21.00)'),
			array('start_time', 'compare', 'operator'=>'<=', 'compareValue'=>21*3600, 'message'=>'некорректно указано время (с 7.00 до 21.00)'),

			array('end_time', 'compare', 'operator'=>'>=', 'compareValue'=>8*3600, 'message'=>'некорректно указано время (с 8.00 до 22.00)'),
			array('end_time', 'compare', 'operator'=>'<=', 'compareValue'=>22*3600, 'message'=>'некорректно указано время (с 8.00 до 22.00)'),

			array('day_of_week', 'compare', 'operator'=>'>=', 'compareValue'=>0, 'message'=>'Invalid date'),
			array('day_of_week', 'compare', 'operator'=>'<=', 'compareValue'=>6, 'message'=>'Invalid date'),

			array('start_time, end_time', 'timeCheck'),

			// The following rule is used by search().
			array('id, status, type, name', 'safe', 'on'=>'search'),
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

	public function behaviors()
	{
		return array(
			'ModelTimeBehavior' => array(
				'class'     => 'application.components.behaviors.ModelTimeBehavior',
			)
		);
	}

	public function init()
	{
		parent::init();
		$this->onAfterSave = array($this, 'makeLinks');
	}

	/**
	 * Создание линков при сохранении шаблонов.
	 * Attention! Сохраняется только при создании шаблона
	 * и смене типа на регулярное событие
	 */
	public function makeLinks()
	{
		if ($this->getIsNewRecord()) {
			$count = $this->type == self::TYPE_SINGLE ? 1 : 4;
			$initTime = $this->init_time; // время начала события
		} else {
			$count = $this->type == self::TYPE_SINGLE ? 0 : 3;
			$initTime = $this->init_time; // время начала события
		}

		for ($i=0; $i<$count; $i++) {
			$event = new Event();
			$event->template_id = $this->id;
			$event->hall_id = $this->hall_id; // ?
			$event->center_id = $this->center_id;
			$event->service_id = $this->service_id;
			$event->user_id = $this->user_id;
			$event->day_of_week = $this->day_of_week; // ?
			$event->name = $this->name;

			$event->start_time = $initTime + $this->start_time;
			$event->end_time = $initTime + $this->end_time;
			$event->save(false);

			$initTime += 7*24*3600; // интервал событий - неделя
		}

	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'status' => 'Статус',
			'type' => 'Тип события',
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
	 * Получение ID ветки событий (ссылается на первый event в цепочке)
	 */
	public function getBranchId()
	{
		return empty($this->parent_id) ? $this->id : $this->parent_id;
	}


	public function updateFromEvent($event, $type, $initTime)
	{
		if ( !$event instanceof Event || !isset(self::$typeNames[$type]) )
			throw new CHttpException(500);

		$this->name = $event->name;
		$this->hall_id = $event->hall_id;
		$this->user_id = $event->user_id;
		$this->center_id = $event->center_id;
		$this->service_id = $event->service_id;
		$this->day_of_week = $event->day_of_week;
		$this->start_time = $event->start_time;
		$this->end_time = $event->end_time;
		$this->type = $type;
		$this->init_time = $initTime;
	}
}
