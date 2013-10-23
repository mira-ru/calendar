<?php

/**
 * This is the model class for table "center".
 *
 * The followings are the available columns in table 'center':
 * @property integer $id
 * @property string $name
 * @property string $color
 * @property integer $status
 * @property integer $overview
 * @property integer $detailed_view
 * @property integer $position
 * @property integer $create_time
 * @property integer $update_time
 */
class Center extends CActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;

	public static $statusNames = array(
		self::STATUS_ACTIVE => 'Активен',
		self::STATUS_DELETED => 'Удален',
	);

	const MODEL_TYPE = 6;

	public function init()
	{
		$this->onAfterSave = array('Config', 'generateCss');
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'center';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_DELETED)),
			array('overview, detailed_view', 'in', 'range'=>array(Config::VIEW_DAY, Config::VIEW_WEEK, Config::VIEW_MONTH)),
			array('name', 'length', 'max'=>255),
			array('color', 'length', 'max'=>7),
			array('color', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, status', 'safe', 'on'=>'search'),
		);
	}

	public function behaviors()
	{
		return array(
			'ModelTimeBehavior' => array(
				'class'     => 'application.components.behaviors.ModelTimeBehavior',
			),
			'PositionBehavior' => array(
				'class'     => 'application.components.behaviors.PositionBehavior',
				'whereLimitField' => 'status',
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
			'name' => 'Название',
			'overview' => 'Общий вид',
			'detailed_view' => 'Подробный вид',
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
		// @todo Please modify the following code to remove attributes that should not be searched.

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
		$criteria->order = 'position ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Center the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получение ID первого центра
	 */
	public static function getFirstId()
	{
		$sql = 'SELECT id FROM center WHERE status='.self::STATUS_ACTIVE.' ORDER BY position ASC LIMIT 1';
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}
}
