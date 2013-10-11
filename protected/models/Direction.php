<?php

/**
 * This is the model class for table "direction".
 *
 * The followings are the available columns in table 'direction':
 * @property integer $id
 * @property integer $status
 * @property integer $service_id
 * @property string $name
 * @property string $url
 * @property string price
 * @property integer image_id
 * @property integer $create_time
 * @property integer $update_time
 */
class Direction extends CActiveRecord
{
	public $center_id;
	// Загруженный файл
	public $file;

	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;

	public static $statusNames = array(
		self::STATUS_ACTIVE => 'Активен',
		self::STATUS_DELETED => 'Удален',
	);


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'direction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('center_id, service_id, image_id', 'numerical', 'integerOnly'=>true),
			array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_DELETED)),
			array('name', 'length', 'max'=>255),
			array('center_id', 'required'),
			array('service_id', 'required'),
			array('url', 'url', 'allowEmpty' => true,
				'message' => 'Неправильный URL страницы',
				'pattern'=>'/^(http(s?)\:\/\/)?(([0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя][0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_-]*)(\.[0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя][0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_-]*)+(\/[0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя][0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_-]*)*(\/?(\?([0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя][-0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_\[\]]*(=[-0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_\[\]\,\'\\\+%\$#]*){0,1}(&[0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя][-0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_\[\]]*(=[-0-9a-zA-ZА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя_\[\]\,\'\\\+%\$#]*){0,1})*){0,1})?))$/i',
			),
			array('url', 'length', 'max'=>512),
			array('file', 'file', 'types'=> 'jpg, bmp, png, jpeg', 'maxFiles'=> 1, 'maxSize' => 10737418240, 'allowEmpty' => true),
			array('desc', 'length', 'max'=>5000),
			array('price', 'length', 'max'=>2048),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, center_id, service_id, name, status, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}

	public function init()
	{
		parent::init();
		$this->onAfterFind = array($this, 'setCenter');
		$this->onAfterSave = array($this, 'setCenter');
	}

	public function setCenter()
	{
		$service = $this->service;
		if ($service!==null) {
			$this->center_id = $service->center_id;
		}
	}

	public function behaviors()
	{
		return array(
			'ModelTimeBehavior' => array(
				'class'     => 'application.components.behaviors.ModelTimeBehavior',
			),
			'CSafeContentBehavor' => array(
				'class' => 'application.components.behaviors.CSafeContentBehavior',
				'attributes' => array('desc', 'price'),
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
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'service' => array(self::BELONGS_TO, 'Service', 'service_id'),
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
			'service_id' => 'Услуга',
			'name' => 'Название',
			'url' => 'URL страницы',
			'image_id' => 'Фото',
			'desc' => 'Описание',
			'price' => 'Цена',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);

		if (empty($this->status)) {
			$criteria->compare('t.status', self::STATUS_ACTIVE);
		} else {
			$criteria->compare('t.status',$this->status);
		}

		$criteria->compare('t.service_id', $this->service_id);
		if (!empty($this->center_id)) {
			$criteria->join = 'INNER JOIN service as s ON s.id=t.service_id';
			$criteria->compare('s.center_id', $this->center_id);
		}

		$request = Yii::app()->getRequest();
		if (($dateFrom = $request->getParam('date_from'))) {
			$criteria->compare('t.create_time', '>=' . strtotime($dateFrom));
		}
		if (($dateTo = $request->getParam('update_to'))) {
			$criteria->compare('t.update_time', '<' . strtotime('+1 day', strtotime($dateTo)));
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
	 * Получение списка направлений, у которых есть события
	 * в указанном интервале времени
	 * @param $startTime
	 * @param $endTime
	 * @param $serviceId
	 */
	public static function getActiveByTime($startTime, $endTime, $serviceId)
	{
		$criteria = new CDbCriteria();
		$criteria->select = 'DISTINCT t.*';
		$criteria->join = 'INNER JOIN event as e ON e.direction_id=t.id AND e.start_time >= :start AND e.end_time < :end AND e.service_id=:sid';
		$criteria->condition = 't.service_id=:sid AND t.status=:st';
		$criteria->params = array(':start'=>$startTime, ':end'=>$endTime, ':sid'=>$serviceId, ':st'=>self::STATUS_ACTIVE);
		$criteria->index = 'id';

		return self::model()->findAll($criteria);
	}

	/**
	 * Проверка на необходимость выводить линк на напрвление на фронте
	 * @return bool
	 */
	public function checkShowLink()
	{
		return !empty($this->desc);
	}
}
