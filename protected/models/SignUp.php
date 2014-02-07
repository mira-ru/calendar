<?php

/**
 * This is the model class for table "sign_up".
 *
 * The followings are the available columns in table 'sign_up':
 * @property integer $id
 * @property integer $eventId
 * @property string $name
 * @property string $email
 * @property string $comment
 * @property string $is_first
 * @property string $is_need_consult
 * @property string $phone
 * @property integer $create_time
 */
class SignUp extends CActiveRecord
{

	public function tableName()
	{
		return 'sign_up';
	}

	public function rules()
	{
		return array(
			array('name, phone, eventId, create_time', 'required'),
			array('eventId, create_time, is_need_consult, is_first', 'numerical', 'integerOnly'=>true),
			array('name, phone, email', 'length', 'max'=>255),
			array('comment', 'length', 'max'=>5000),
			array('id, name, phone, email, eventId, create_time, is_need_consult, is_first', 'safe', 'on'=>'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Имя, Фамилия',
			'phone' => 'Телефон',
			'email' => 'Email',
			'comment' => 'Комментарий',
			'is_first' => 'Первое обращение',
			'is_need_comment' => 'Требуется консультация',
			'eventId' => 'ID события',
			'create_time' => 'Дата записи',
		);
	}


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * Возвращает текст для вставки в тело емаил-уведомления о новой записи
	 * на событие
	 * @return string
	 */
	public function getNotifierMessage()
	{
		$signUpTime = date('d.m.Y H:i:s');
		$event = Event::model()->findByPk($this->eventId);
		$direction = Direction::model()->findByPk($event->direction_id);
		$eventTime = date("d.m.Y", $event->start_time)." ".date("H:i", $event->start_time)."-".date("H:i", $event->end_time);
		$hallName = empty($event->hall) ? 'Зал не указан' : $event->hall->name;
		$masterNames = empty($event->users) ? 'Мастера не указаны' : $event->renderAdminUsers();
		$dayOfWeek = DateMap::$smallDayMap[$event->day_of_week];
		$comment = nl2br($this->comment);
		$is_first = $this->is_first ? 'Да' : 'Нет';
		$is_need_consult = $this->is_need_consult ? 'Да' : 'Нет';

		$message = "Имя, фамилия: {$this->name}<br>
Телефон: {$this->phone}<br>
Email: {$this->email}<br>
Первое обращение: {$is_first}<br>
Требуется консультация: {$is_need_consult}<br>
Комментарий: {$comment}<br><br>

Запись на событие: {$direction->name}<br>
Зал: {$hallName}<br><br>

День недели: {$dayOfWeek}<br>
Время события: {$eventTime}<br>
Мастера: {$masterNames}<br>
";

		return $message;
	}
}
