<?php

/**
 * This is the model class for table "event".
 *
 * The followings are the available columns in table 'event':
 * @property integer $id
 * @property integer $template_id
 * @property integer $hall_id
 * @property integer $direction_id
 * @property integer $center_id
 * @property integer $service_id
 * @property integer $is_draft
 * @property integer $image_id
 * @property integer $day_of_week
 * @property string $desc
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $create_time
 * @property integer $update_time
 */
class Event extends CActiveRecord
{
	const MODEL_TYPE = 4;

	// для поиска по типам
	public $event_type;
	// Поиск по юзерам
	public $user_id;
	// Загруженный файл
	public $file;

	public $initTime;

	private $_template = null;
	private $_users = null;

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
	 * @var bool флаг отключает логирование события
	 */
	public $disableLog = false;

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
			array('center_id, service_id, hall_id, direction_id, image_id', 'numerical', 'integerOnly'=>true),

			array('service_id', 'required', 'message'=>'Укажите группу'),
			array('center_id', 'required', 'message'=>'Укажите центр'),
			array('direction_id', 'required', 'message'=>'Укажите направление'),
			array('hall_id', 'required', 'message'=>'Укажите зал'),
			array('is_draft', 'in', 'range'=>array(EventTemplate::DRAFT_BOOKING, EventTemplate::DRAFT_PREBOOKING, EventTemplate::DRAFT_NO)),

			array('desc', 'length', 'max'=>5000),
			array('day_of_week', 'compare', 'operator'=>'>=', 'compareValue'=>0, 'message'=>'Invalid date'),
			array('day_of_week', 'compare', 'operator'=>'<=', 'compareValue'=>6, 'message'=>'Invalid date'),

			array('start_time, end_time', 'timeCheck', 'max'=>23*3600, 'min'=>7*3600),
			array('file', 'file', 'types'=> 'jpg, bmp, png, jpeg', 'maxFiles'=> 1, 'maxSize' => 10737418240, 'allowEmpty' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, direction_id, event_type, service_id, hall_id, center_id, is_draft, template_id', 'safe', 'on'=>'search'),
			array('forceSave, initTime', 'safe'),
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
		if ( empty($this->$attribute) ) {
			$message = 'Время не указано';
			$this->addError($attribute, $message);
			return false;
		}

		$time = $this->$attribute - DateMap::currentDay($this->$attribute);
		if (isset($params['min']) && $time < $params['min']) {
			$message = 'некорректно указано время (с 7.00 до 23.00)';
			$this->addError($attribute, $message);
			return false;
		}

		if (isset($params['max']) && $time > $params['max']) {
			$message = 'некорректно указано время (с 7.00 до 23.00)';
			$this->addError($attribute, $message);
			return false;
		}

		if ($this->start_time >= $this->end_time) {
			$message = 'Время начала должно быть меньше времени окончания';
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
			'service' => array(self::BELONGS_TO, 'Service', 'service_id'),
			'hall' => array(self::BELONGS_TO, 'Hall', 'hall_id'),
			'center' => array(self::BELONGS_TO, 'Center', 'center_id'),
			'direction' => array(self::BELONGS_TO, 'Direction', 'direction_id'),
		);
	}

	public function behaviors()
	{
		return array(
			'TextAreaBehavior' => array(
				'class' => 'application.components.behaviors.TextAreaBehavior',
				'attributes' => array('desc'),
			),
			'UserLogBehavior' => array(
				'class'     => 'application.components.behaviors.UserLogBehavior',
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
			'hall_id' => 'Зал',
			'desc' => 'Описание',
			'start_time' => 'Дата начала',
			'end_time' => 'Дата окончания',
			'day_of_week' => 'День недели',
			'create_time' => 'Дата создания',
			'update_time' => 'Дата обновления',
			'image_id' => 'Фото',
			'users' => 'Мастера',
			'event_type' => 'Тип события',
			'is_draft' => 'Состояние',
		);
	}

	public function init()
	{
		parent::init();
		$this->onAfterValidate = array($this, 'validatePeriod');
		Report::initEvents($this);
	}

	public $oldState;

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

		$criteria->select = 't.*';
		$criteria->compare('t.id',$this->id);
		if (!empty($this->user_id)) {
			$criteria->join = 'INNER JOIN event_user as eu ON eu.template_id=t.template_id';
			$criteria->compare('eu.user_id', $this->user_id);
		}

		$criteria->compare('t.service_id', $this->service_id);
		$criteria->compare('t.hall_id', $this->hall_id);
		$criteria->compare('t.center_id', $this->center_id);
		$criteria->compare('t.direction_id', $this->direction_id);
		$criteria->compare('t.is_draft', $this->is_draft);
		$criteria->compare('t.template_id', $this->template_id);

		$request = Yii::app()->getRequest();
		if (($dateFrom = $request->getParam('date_from'))) {
			$criteria->compare('t.start_time', '>=' . strtotime($dateFrom));
		} else {
			$criteria->compare('t.start_time', '>=' . DateMap::currentDay(time()));
		}
		if (($dateTo = $request->getParam('date_to'))) {
			$criteria->compare('t.start_time', '<' . (strtotime($dateTo)+86400));
		}
		if ( ($dateUpdate = $request->getParam('date_update')) ) {
			$criteria->compare('t.update_time', '>='.strtotime($dateUpdate));
			$criteria->compare('t.update_time', '<'.strtotime('+1 day', strtotime($dateUpdate)));
		}

		if ($direction = $request->getParam('direction')) {
			$criteria->join .= ' INNER JOIN direction as d ON d.id=t.direction_id';

			$direction = trim($direction);
			$criteria->compare('d.name',$direction,true);
		}

		if (!empty($this->event_type)) {
			$criteria->join .= ' INNER JOIN event_template as et ON et.id=t.template_id';
			$criteria->compare('et.type', $this->event_type);
		}
		$criteria->order = 't.update_time DESC, t.id ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>50,
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
	public static function getByTime($startTime, $endTime, $centerId=null, $directionId=null, $serviceId=null, $userId=null, $hallId=null, $showDraft=false, $limit=null)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'start_time >= :start AND end_time < :end';
		$criteria->order = 'start_time ASC';
		$criteria->params = array(':start'=>$startTime, ':end'=>$endTime);

		if ( !empty($centerId) ) {
			$criteria->compare('center_id', $centerId);
		}
		if ( !empty($directionId) ) {
			$criteria->compare('direction_id', $directionId);
		}
		if ( !empty($serviceId) ) {
			$criteria->compare('service_id', $serviceId);
		}
		if ( !empty($userId) ) {
			$criteria->join = 'INNER JOIN event_user as eu ON eu.template_id=t.template_id';
			$criteria->compare('eu.user_id', $userId);
		}
		if ( !empty($hallId) ) {
			$criteria->compare('hall_id', $hallId);
		}
		if ( !$showDraft ) {
			$criteria->compare('is_draft', EventTemplate::DRAFT_NO);
		}
		if ($limit) {
			$criteria->limit = $limit;
		}

		return self::model()->findAll($criteria);
	}

	/**
	 * Получение списка дней с событиями
	 * (по центру и по направлениям)
	 */
	public static function getActiveDays($startTime, $endTime, $centerId=null, $directionId=null, $serviceId=null, $userId=null, $hallId=null, $showDraft=false)
	{
		$events = self::getByTime($startTime, $endTime, $centerId, $directionId, $serviceId, $userId, $hallId, $showDraft);
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
		$event->is_draft = $template->is_draft;
		$event->image_id = $template->image_id;
		$event->desc = $template->desc;
		$event->direction_id = $template->direction_id;
		$event->hall_id = $template->hall_id;
		$event->center_id = $template->center_id;
		$event->service_id = $template->service_id;
		$event->day_of_week = $template->day_of_week;
		$event->start_time = $template->start_time + $initTime;
		$event->end_time = $template->end_time + $initTime;
		$event->template_id = $template->id;
		$event->create_time = $template->create_time;
		$event->update_time = $template->update_time;

		$event->save(false);
	}

	/**
	 * Валидация нового временного интервала события (линка)
	 * @param $template EventTemplate
	 * @param $time integer
	 * @param $similar array
	 * @return bool|array
	 */
	public static function eventPeriodChecker($start_time, $end_time, $hall_id, $time, &$similar=null)
	{
		$condition = '((start_time < :et and :et <= end_time) or (:st <= start_time and start_time < :et) or '
			. '(:st < end_time and end_time <= :et))';

		$condition.= ' and hall_id=:hid';

		$params = array(
			':st'=>$start_time + $time,
			':et'=>$end_time + $time,
			':hid'=>$hall_id,
		);

		$similar = Yii::app()->db->createCommand()->select('id')->from(self::model()->tableName())
			->where($condition, $params)->queryColumn();

		if ( count($similar) == 0 )
			return true;
		else
			return false;
	}

	public function validatePeriod()
	{
		if ( $this->forceSave )
			return true;

		$result = self::eventPeriodChecker($this->start_time, $this->end_time, $this->hall_id, 0, $this->similarEvents);

		if(($key = array_search($this->id, $this->similarEvents)) !== false) {
			unset($this->similarEvents[$key]);
		}

		if ( count($this->similarEvents) > 0 )
			$this->addError('error', 'Временной интервал события пересекается с другими событиями');
	}

	/**
	 * @param $template EventTemplate
	 * @param $dayTime смещение в днях при обновлениии события
	 * @throws Exception
	 */
	public function updateYoungEvents($template, $dayTime)
	{
		if ($this->getIsNewRecord()) {
			return false;
		}
		if ( !$template instanceof EventTemplate ) {
			throw new CException('Invalid template', 500);
		}

		$template->is_draft = $this->is_draft;
		$template->image_id = $this->image_id;
		$template->desc = $this->desc;
		$template->direction_id = $this->direction_id;
		$template->hall_id = $this->hall_id;
		$template->center_id = $this->center_id;
		$template->service_id = $this->service_id;
		$template->day_of_week = $this->day_of_week;
		$template->start_time = $this->start_time - DateMap::currentDay($this->start_time);
		$template->end_time = $this->end_time - DateMap::currentDay($this->end_time);
		$template->init_time += $dayTime;

		// валидация шаблона по времени
		if ( !$template->validateEventsPeriod() )
			return false;

		$template->save(false);

		$events = self::model()->findAllByAttributes(array('template_id'=>$template->id), 'id>:id', array(':id'=>$this->id));

		/** @var $transaction CDbTransaction */
		$transaction = Yii::app()->db->beginTransaction();

		try {
			/** @var $event Event */
			foreach ($events as $event) {
				$event->hall_id = $template->hall_id;
				// расчет новых времени начала (смещаем все события на разницу в init_time и start_time)
				$event->day_of_week = $template->day_of_week;
				$event->start_time = DateMap::currentDay($event->start_time) + $dayTime + $template->start_time;
				$event->end_time = DateMap::currentDay($event->end_time) + $dayTime + $template->end_time;

				$event->is_draft = $template->is_draft;
				$event->image_id = $template->image_id;
				$event->desc = $template->desc;
				$event->direction_id = $template->direction_id;
				$event->center_id = $template->center_id;
				$event->service_id = $template->service_id;
				$event->create_time = $template->create_time;
				$event->update_time = $template->update_time;
				$event->disableLog = true;
				$event->save(false);
			}
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
			throw $e;
		}

		return true;
	}

	/**
	 * Список объектов юзеров шаблона
	 */
	public function getUsers()
	{
		if ($this->_users !== null) {
			return $this->_users;
		}

		if ($this->getIsNewRecord()) {
			$this->_users = array();
		} elseif ( $this->_users === null ) {
			$criteria = new CDbCriteria();
			$criteria->select = 'DISTINCT t.*';
			$criteria->join = 'INNER JOIN event_user as eu ON eu.user_id=t.id';
			$criteria->condition = 'eu.template_id=:tid';
			$criteria->params = array(':tid'=>$this->template_id);

			$this->_users = User::model()->findAll($criteria);
		}

		return $this->_users;
	}

	/**
	 * Вывод юзеров для админки
	 */
	public function renderAdminUsers()
	{
		$tmp = '';
		foreach ($this->getUsers() as $user) {
			if (!empty($tmp)) {
				$tmp .= ', ';
			}
			$tmp .= CHtml::link(
					$user->name,
					Yii::app()->controller->createUrl('/admin/event/index', array('Event[user_id]'=> $user->id) ));
		}
		return $tmp;
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
