<?php

/**
 * This is the model class for table "hall".
 *
 * The followings are the available columns in table 'hall':
 * @property integer $id
 * @property integer $template_id
 * @property integer $hall_id
 * @property integer $direction_id
 * @property integer $center_id
 * @property integer $service_id
 * @property integer $day_of_week
 * @property string $desc
 * @property integer $user_id
 * @property integer $start_time
 * @property integer $end_time
 */
class Event extends CActiveRecord
{
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
			array('center_id, service_id, user_id, hall_id, direction_id', 'numerical', 'integerOnly'=>true),

			array('user_id', 'required', 'message'=>'Укажите мастера'),
			array('service_id', 'required', 'message'=>'Укажите группу'),
			array('center_id', 'required', 'message'=>'Укажите центр'),
			array('direction_id', 'required', 'message'=>'Укажите направление'),

			array('start_time', 'compare', 'operator'=>'>=', 'compareValue'=>7*3600, 'message'=>'некорректно указано время (с 7.00 до 21.00)'),
			array('start_time', 'compare', 'operator'=>'<=', 'compareValue'=>21*3600, 'message'=>'некорректно указано время (с 7.00 до 21.00)'),

			array('end_time', 'compare', 'operator'=>'>=', 'compareValue'=>8*3600, 'message'=>'некорректно указано время (с 8.00 до 22.00)'),
			array('end_time', 'compare', 'operator'=>'<=', 'compareValue'=>22*3600, 'message'=>'некорректно указано время (с 8.00 до 22.00)'),

			array('desc', 'length', 'max'=>1024),

			array('start_time, end_time', 'timeCheck'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, direction_id, service_id, hall_id, center_id', 'safe', 'on'=>'search'),
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
			'direction' => array(self::BELONGS_TO, 'Direction', 'direction_id'),
		);
	}

	public function behaviors()
	{
		return array(
			'CSafeContentBehavor' => array(
				'class' => 'application.components.behaviors.CSafeContentBehavior',
				'attributes' => array('desc'),
				'options' => array(
					'HTML.AllowedElements' => array(
						'a' => true,
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
			'center_id' => 'Центр',
			'service_id' => 'Группа услуг',
			'direction_id' => 'Направление',
			'user_id' => 'Мастер',
			'hall_id' => 'Зал',
			'desc' => 'Описание',
			'start_time' => 'Дата начала',
			'end_time' => 'Дата окончания',
			'day_of_week' => 'День недели',
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
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('service_id', $this->service_id);
		$criteria->compare('hall_id', $this->hall_id);
		$criteria->compare('center_id', $this->center_id);
		$criteria->compare('direction_id', $this->direction_id);

		$request = Yii::app()->getRequest();
		if (($dateFrom = $request->getParam('date_from'))) {
			$criteria->compare('start_time', '>=' . strtotime($dateFrom));
		} else {
			$criteria->compare('start_time', '>=' . DateMap::currentDay(time()));
		}
		if (($dateTo = $request->getParam('date_to'))) {
			$criteria->compare('start_time', '<' . (strtotime($dateTo)+86400));
		}

		$sort = new CSort();
		$sort->defaultOrder = array('start_time' => CSort::SORT_ASC);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
			'pagination'=>array(
				'pageSize'=>20,
			),
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

	/**
	 * Получение событий в интервале времени
	 * @param $startTime
	 * @param $endTime
	 * @return array|CActiveRecord
	 */
	public static function getByTime($startTime, $endTime, $centerId, $directionId=null, $serviceId=null)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'start_time >= :start AND end_time < :end AND center_id=:cid';
		$criteria->order = 'start_time ASC';
		$criteria->params = array(':start'=>$startTime, ':end'=>$endTime, ':cid'=>$centerId);

		if ( !empty($directionId) ) {
			$criteria->compare('direction_id', $directionId);
		}
		if ( !empty($serviceId) ) {
			$criteria->compare('service_id', $serviceId);
		}

		return self::model()->findAll($criteria);
	}

	/**
	 * Получение списка дней с событиями
	 * (по центру и по направлениям)
	 */
	public static function getActiveDays($startTime, $endTime, $centerId, $directionId=null, $serviceId=null)
	{
		$events = self::getByTime($startTime, $endTime, $centerId, $directionId, $serviceId);
		$result = array();
		/** @var $event Event */
		foreach ($events as $event) {
			$dayTime = DateMap::currentDay($event->start_time);
			$result[$dayTime] = $dayTime;
		}

		return $result;
	}

	/**
	 * Создание нового события (линка)
	 * @param $template EventTemplate
	 */
	public static function createEvent($template, $time)
	{
		$initTime = strtotime('TODAY', $time);

		$event = new Event();
		$event->desc = $template->desc;
		$event->direction_id = $template->direction_id;
		$event->hall_id = $template->hall_id;
		$event->user_id = $template->user_id;
		$event->center_id = $template->center_id;
		$event->service_id = $template->service_id;
		$event->day_of_week = $template->day_of_week;
		$event->start_time = $template->start_time + $initTime;
		$event->end_time = $template->end_time + $initTime;
		$event->template_id = $template->id;

		$event->save(false);
	}

	/**
	 * Обновляет младшие события
	 * @param $event
	 * @throws CHttpException
	 */
//	public function updateLinks($template)
//	{
//		if ( !$template instanceof EventTemplate )
//			throw new CHttpException(500);
//
//		$events = self::model()->findAllByAttributes(array('template_id'=>$this->template_id), 'id>:id', array(':id'=>$this->id));
//
//		/** @var $item Event */
//		foreach ($events as $item) {
//			$initTime = $template->init_time;
//
//
//			$item->setAttributes($this->attributes, false);
//			$item->save(false);
//		}
//
//	}
}
