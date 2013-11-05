<?php

/**
 * This is the model class for table "service".
 *
 * The followings are the available columns in table 'service':
 * @property integer $id
 * @property integer $status
 * @property integer $center_id
 * @property string $name
 * @property string $color
 * @property integer $create_time
 * @property integer $update_time
 */
class Service extends CActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;

	public static $statusNames = array(
		self::STATUS_ACTIVE => 'Активен',
		self::STATUS_DELETED => 'Удален',
	);

	const MODEL_TYPE = 2;

	public function init()
	{
		$this->onAfterSave = array('Config', 'generateCss');
		$this->onAfterSave = array($this, 'resetParams');
	}

	/**
	 * Поддержание параметров в связанных событиях и направлениях
	 * @return bool
	 */
	public function resetParams()
	{
		if ($this->getIsNewRecord()) {
			return true;
		}

		Direction::model()->updateAll(array(
			'center_id'=>$this->center_id,
		), 'service_id=:sid', array(':sid'=>$this->id));

		Event::model()->updateAll(array(
			'center_id'=>$this->center_id,
			'service_id'=>$this->id
		), 'service_id=:sid', array(':sid'=>$this->id));

		EventTemplate::model()->updateAll(array(
			'center_id'=>$this->center_id,
			'service_id'=>$this->id
		), 'service_id=:sid', array(':sid'=>$this->id));
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'service';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('center_id', 'numerical', 'integerOnly'=>true),
			array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_DELETED)),
			array('name', 'length', 'max'=>255),
			array('color', 'length', 'max'=>7),
			array('color', 'required'),
			array('center_id', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, status, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}

	public function behaviors()
	{
		return array(
			'ModelTimeBehavior' => array(
				'class'     => 'application.components.behaviors.ModelTimeBehavior',
			)
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
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
			'status' => 'Статус',
			'center_id' => 'Центр',
			'name' => 'Название',
			'color' => 'Цвет',
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
	 * @return Service the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получение списка услуг, у которых есть события в центре по
	 * указанному интервалу времени
	 * @param $startTime
	 * @param $endTime
	 * @param $centerId
	 */
	public static function getActiveByTime($startTime, $endTime, $centerId, $showDraft=false)
	{
		FirePHP::getInstance()->fb($showDraft);
		$criteria = new CDbCriteria();
		$criteria->select = 'DISTINCT t.*';
		$criteria->join = 'INNER JOIN event as e ON e.service_id=t.id AND e.start_time >= :start AND e.end_time < :end AND e.center_id=:cid';
		$criteria->condition = 't.center_id=:cid AND t.status=:st';
		$criteria->params = array(':start'=>$startTime, ':end'=>$endTime, ':cid'=>$centerId, ':st'=>self::STATUS_ACTIVE);
		$criteria->index = 'id';
		$criteria->order = 't.name ASC';

		if (!$showDraft) {
			$criteria->condition .= ' AND e.is_draft='.EventTemplate::DRAFT_NO;
		}


		return self::model()->findAll($criteria);
	}
}
