<?php

/**
 * This is the model class for table "event_template".
 *
 * The followings are the available columns in table 'EventTemplate':
 * @property integer $id
 * @property integer $type
 * @property integer $status
 * @property integer $hall_id
 * @property integer $direction_id
 * @property integer $center_id
 * @property integer $service_id
 * @property integer $is_draft
 * @property integer $image_id
 * @property integer $day_of_week
 * @property string $desc
 * @property string $comment
 * @property integer $init_time - день первого события(timestamp)
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $create_time
 * @property integer $update_time
 */
class EventTemplate extends CActiveRecord
{
	// Загруженный файл
	public $file;
	// Флаг создания линков после сохранения
	public $makeLinks = true;

	private $_users = null;

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

	const DRAFT_YES = 1;
	const DRAFT_NO = 0;
	public static $draftNames = array(
		self::DRAFT_YES => 'Черновик',
		self::DRAFT_NO => 'Опубликован',
	);

	/**
	 * @var bool флаг принудительного сохранения без валидации периодов событий
	 */
	public $forceSave = false;

	/**
	 * @var array массив id событий, которые пересекаются с событиями текущего шаблона
	 */
	public $similarEvents = array();

	/**
	 * @var атрибут используется для генерации ошибки валидации
	 */
	public $error = null;

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
			array('center_id, service_id, hall_id, direction_id, image_id', 'numerical', 'integerOnly'=>true),
			array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_DISABLED)),
			array('type', 'in', 'range'=>array(self::TYPE_SINGLE, self::TYPE_REGULAR)),
			array('is_draft', 'in', 'range'=>array(self::DRAFT_YES, self::DRAFT_NO)),
			array('service_id', 'required', 'message'=>'Укажите группу'),
			array('center_id', 'required', 'message'=>'Укажите центр'),
			array('direction_id', 'required', 'message'=>'Укажите направление'),

			array('desc, comment', 'length', 'max'=>5000),

			array('start_time', 'compare', 'operator'=>'>=', 'compareValue'=>7*3600, 'message'=>'некорректно указано время (с 7.00 до 21.00)'),
			array('start_time', 'compare', 'operator'=>'<=', 'compareValue'=>21*3600, 'message'=>'некорректно указано время (с 7.00 до 21.00)'),

			array('end_time', 'compare', 'operator'=>'>=', 'compareValue'=>8*3600, 'message'=>'некорректно указано время (с 8.00 до 22.00)'),
			array('end_time', 'compare', 'operator'=>'<=', 'compareValue'=>22*3600, 'message'=>'некорректно указано время (с 8.00 до 22.00)'),

			array('day_of_week', 'compare', 'operator'=>'>=', 'compareValue'=>0, 'message'=>'Invalid date'),
			array('day_of_week', 'compare', 'operator'=>'<=', 'compareValue'=>6, 'message'=>'Invalid date'),

			array('start_time, end_time', 'timeCheck'),
			array('file', 'file', 'types'=> 'jpg, bmp, png, jpeg', 'maxFiles'=> 1, 'maxSize' => 10737418240, 'allowEmpty' => true),

			array('error', 'validateEventsPeriod'),

			array('users, forceSave', 'safe'),

			// The following rule is used by search().
//			array('id, status, type, name', 'safe', 'on'=>'search'),
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
			),
			'TextAreaBehavior' => array(
				'class' => 'application.components.behaviors.TextAreaBehavior',
				'attributes' => array('desc'),
			),
		);
	}

	public function init()
	{
		parent::init();
		$this->onAfterSave = array($this, '_saveUsers');
		$this->onAfterSave = array($this, 'makeLinks');
		$this->onAfterValidate = array($this, 'validateEventsPeriod');
	}

	/**
	 * Создание линков при сохранении шаблонов.
	 * Attention! Сохраняется только при создании шаблона
	 * и смене типа на регулярное событие
	 */
	public function makeLinks()
	{
		if ($this->status != self::STATUS_ACTIVE || !$this->makeLinks)
			return false;

		if ($this->getIsNewRecord()) {
			$count = $this->type == self::TYPE_SINGLE ? 1 : 4;
			$initTime = $this->init_time; // время начала события
		} else {
			$count = $this->type == self::TYPE_SINGLE ? 0 : 3;
			$initTime = $this->init_time + DateMap::TIME_WEEK; // время начала события
		}

		for ($i=0; $i<$count; $i++) {

			Event::createEvent($this, $initTime);
			$initTime += DateMap::TIME_WEEK; // интервал событий - неделя
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
			'center_id' => 'Центр',
			'service_id' => 'Группа услуг',
			'direction_id' => 'Направление',
			'hall_id' => 'Зал',
			'image_id' => 'Фото',
			'desc' => 'Описание',
			'create_time' => 'Дата создания',
			'update_time' => 'Дата обновления',
			'users' => 'Мастера',
			'comment' => 'Внутренние комментарии',
			'is_draft' => 'Состояние',
		);
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

	public function updateFromEvent($event, $type, $initTime)
	{
		if ( !$event instanceof Event || !isset(self::$typeNames[$type]) )
			throw new CHttpException(500);

//		$this->name = $event->name;
		$this->is_draft = $event->is_draft;
		$this->image_id = $event->image_id;
		$this->desc = $event->desc;
		$this->direction_id = $event->direction_id;
		$this->hall_id = $event->hall_id;
		$this->center_id = $event->center_id;
		$this->service_id = $event->service_id;
		$this->day_of_week = $event->day_of_week;
		$this->start_time = $event->start_time;
		$this->end_time = $event->end_time;
		$this->type = $type;
		$this->init_time = $initTime;
	}

	/**
	 * Список линкованых юзеров к шаблону
	 */
	public function getUsers()
	{
		if ($this->_users !== null) {
			return $this->_users;
		}

		if ($this->getIsNewRecord()) {
			$this->_users = array();
		} elseif ( $this->_users === null ) {
			$sql = 'SELECT user_id FROM event_user WHERE template_id='.intval($this->id);
			$this->_users = Yii::app()->db->createCommand($sql)->queryColumn();
		}

		return $this->_users;
	}

	public function setUsers($value)
	{
		if (is_array($value)) {
			$this->_users = $value;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Сохраняет список пользователей в связную таблицу
	 * NOTICE: do not call directly
	 */
	public function _saveUsers()
	{
		if ($this->_users === null) { // Небыло обновлений
			return true;
		}

		/** @var $transaction CDbTransaction */
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$sql = 'DELETE FROM event_user WHERE template_id='.intval($this->id);
			Yii::app()->db->createCommand($sql)->execute();

			if ( !empty($this->_users) ) {
				$sql = 'INSERT INTO event_user (`template_id`, `user_id`) VALUES ';
				$cnt = 0;
				foreach ($this->_users as $user) {
					$id = intval($user);
					if ($cnt > 0) {
						$sql .= ',';
					} else {
						$cnt++;
					}
					$sql .= '('.$this->id.','.$id.')';
				}
				Yii::app()->db->createCommand($sql)->execute();
			}

			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
		}
	}


	/**
	 * Валидация интервалов всех событий текущего шаблона на предмет
	 * пересечения со временем других событий
	 * @return bool
	 */
	public function validateEventsPeriod()
	{
		if ( $this->forceSave )
			return true;

		$count = $this->type == self::TYPE_SINGLE ? 1 : 4;
		$initTime = $this->init_time; // время начала события

		for ($i=0; $i<$count; $i++) {

			$similar = array();
			if ( !Event::eventPeriodChecker($this->start_time, $this->end_time, $this->hall_id, $initTime, $similar) )
				$this->similarEvents+=$similar;

			$initTime += DateMap::TIME_WEEK; // интервал событий - неделя
		}

		// "уборка" дублирующихся id событий
		$this->similarEvents = array_unique($this->similarEvents);

		if ( count($this->similarEvents) > 0 )
			$this->addError('error', 'Временной интервал события пересекается с другими событиями');
	}

}
