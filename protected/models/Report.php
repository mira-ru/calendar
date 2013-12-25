<?php

/**
 * This is the model class for table "report".
 *
 * The followings are the available columns in table 'report':
 * @property integer $id
 * @property integer $model
 * @property integer $model_id
 * @property integer $operation
 * @property string $field
 * @property string $old_value
 * @property string $new_value
 * @property integer $create_time
 */
class Report extends CActiveRecord
{
	// константы моделей
	const MODEL_CENTER = 1;
	const MODEL_DIRECTION = 2;
	const MODEL_HALL = 3;
	const MODEL_SERVICE = 4;
	const MODEL_USER = 5;
	const MODEL_EVENT = 6;

	/**
	 * @var array $models - лейблы моделей
	 */
	static public $models = array(
		self::MODEL_CENTER => 'Центр',
		self::MODEL_DIRECTION => 'Направление',
		self::MODEL_HALL => 'Зал',
		self::MODEL_SERVICE => 'Услуга',
		self::MODEL_USER => 'Мастер',
		self::MODEL_EVENT => 'Событие'
	);

	static public $modelClasses = array(
		'Center' => self::MODEL_CENTER,
		'Direction' => self::MODEL_DIRECTION,
		'Hall' => self::MODEL_HALL,
		'Service' => self::MODEL_SERVICE,
		'User' => self::MODEL_USER,
		'Event' => self::MODEL_EVENT,
	);


	// константы операций
	const OPERATION_CREATE = 1;
	const OPERATION_UPDATE = 2;
	const OPERATION_DELETE = 3;

	/**
	 * @var array $operations  - лейблы операций над моделями
	 */
	static public $operations = array(
		self::OPERATION_CREATE => 'Создано',
		self::OPERATION_UPDATE => 'Отредактировано',
		self::OPERATION_DELETE => 'Удалено',
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model, model_id, operation, create_time', 'required'),
			array('model, model_id, operation, create_time', 'numerical', 'integerOnly'=>true),
			array('field', 'length', 'max'=>255),
			array('old_value, new_value', 'length', 'max'=>3000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, model, model_id, operation, field, old_value, new_value, create_time', 'safe', 'on'=>'search'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'model' => 'Тип записи',
			'model_id' => 'ID записи',
			'operation' => 'Действие',
			'field' => 'Поле',
			'old_value' => 'Старое значение',
			'new_value' => 'Новое значение',
			'create_time' => 'Время',
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
		$criteria->compare('model',$this->model);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('operation',$this->operation);
		$criteria->compare('field',$this->field,true);
		$criteria->compare('old_value',$this->old_value,true);
		$criteria->compare('new_value',$this->new_value,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Report the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function behaviors(){
		return array(
		);
	}


	/**
	 * Сохраняет состояние модели перед ее обновлением
	 * @param $event CModelEvent
	 * @return bool
	 */
	static public function saveOldModelState($event)
	{
		$model = $event->sender;
		if ( $model->isNewRecord )
			return false;

		$tableName = $model->tableName();
		$conn = Yii::app()->db;

		$model->oldState = $conn->createCommand()->from($tableName)
			->where('id=:id', array(':id'=>$model->id))->queryRow();
	}


	/**
	 * Логирование изменений при операции создания и обновления модели
	 * @param $event
	 */
	static public function saveChangesToReport($event)
	{
		$model = $event->sender;

		$newState = $model->attributes;
		$oldState = $model->oldState;

		$logTime = time();

		// модель создана
		if ( is_null($oldState) ) {

			$report = new Report();
			$report->model = self::$modelClasses[get_class($model)];
			$report->model_id = $model->id;
			$report->operation = self::OPERATION_CREATE;
			$report->create_time = $logTime;
			$report->save();

		// модель отредактирована
		} else {

			$diffs = array_diff_assoc($oldState, $newState);
			unset($diffs['update_time']);

			foreach ($diffs as $field=>$diff) {
				$report = new Report();
				$report->model = self::$modelClasses[get_class($model)];
				$report->model_id = $model->id;
				$report->operation = self::OPERATION_UPDATE;
				$report->field = $field;
				$report->old_value = $oldState[$field];
				$report->new_value = $newState[$field];
				$report->create_time = $logTime;
				$report->save();
			}
		}
	}


	/**
	 * Логирование операции удаления модели
	 * @param $event
	 */
	static public function saveDeleteStateToReport($event)
	{
		$model = $event->sender;

		$report = new Report();
		$report->model = self::$modelClasses[get_class($model)];
		$report->model_id = $model->id;
		$report->operation = self::OPERATION_DELETE;
		$report->create_time = time();
		$report->save();
	}


	/**
	 * Навешивание событий на модель для логирования
	 * @param $model
	 * @return bool
	 */
	static public function initEvents(&$model)
	{
		if ( !Yii::app()->user->checkAccess('alexandrovna13') )
			return false;

		$model->onBeforeSave = array('Report', 'saveOldModelState');
		$model->onAfterSave = array('Report', 'saveChangesToReport');
		$model->onAfterDelete = array('Report', 'saveDeleteStateToReport');

		return true;
	}

	public function getModelClass()
	{
		return array_search($this->model, self::$modelClasses);
	}

	public function getFieldLabel()
	{
		$class = $this->getModelClass();
		return $class::model()->getAttributeLabel($this->field);
	}

	public function getOldValue()
	{
		return $this->getHumanVal($this->old_value);
	}

	public function getNewValue()
	{
		return $this->getHumanVal($this->new_value);
	}

	public function getHumanVal($val)
	{
		if ( $this->model == self::MODEL_CENTER ) {
			switch ($this->field) {
				case 'status' : return Center::$statusNames[$val];
				case 'overview' : return Config::$viewNames[$val];
				case 'detailed_view' : return Config::$viewNames[$val];
				default : return $val;
			}
		}

		if ( $this->model == self::MODEL_DIRECTION ) {
			switch ($this->field) {
				case 'status' : return Direction::$statusNames[$val];
				case 'center_id' : return Center::model()->findByPk($val)->name;
				case 'service_id' : return Service::model()->findByPk($val)->name;
				default : return $val;
			}
		}

		if ( $this->model == self::MODEL_HALL ) {
			switch ($this->field) {
				case 'status' : return Hall::$statusNames[$val];
				default : return $val;
			}
		}

		if ( $this->model == self::MODEL_SERVICE ) {
			switch ($this->field) {
				case 'status' : return Service::$statusNames[$val];
				case 'center_id' : return Center::model()->findByPk($val)->name;
				default : return $val;
			}
		}

		if ( $this->model == self::MODEL_USER ) {
			switch ($this->field) {
				case 'status' : return User::$statusNames[$val];
				default : return $val;
			}
		}

		if ( $this->model == self::MODEL_EVENT ) {
			switch ($this->field) {
				case 'center_id' : return Center::model()->findByPk($val)->name;
				case 'service_id' : return Service::model()->findByPk($val)->name;
				case 'direction_id' : return Direction::model()->findByPk($val)->name;
				case 'hall_id' : return Hall::model()->findByPk($val)->name;
				case 'start_time' : return date('d.m.Y H:i', $val);
				case 'end_time' : return date('d.m.Y H:i', $val);
				case 'is_draft' : return EventTemplate::$draftNames[$val];
				default : return $val;
			}
		}

	}
}
