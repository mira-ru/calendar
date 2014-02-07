<?php

/**
 * Виджет генерит зеленую кнопку "Записаться", которая открывает попап
 * с формой для записи на занятие (событие)
 * Class SignUpButtonWidget
 */
class SignUpButtonWidget
{
	/**
	 * @var Event событие, для которого генерится кнопка
	 */
	public $event;

	private $_popupUrl;

	public function init()
	{
		$app = Yii::app();

		/**
		 * УРЛ, по которому будет запрашиваться форма
		 *  для последующей вставки в попап
		 */
		$this->_popupUrl = $app->createUrl('/site/axPopup', array(
			'item'=>$this->event->id,
			'type'=>'e',
		));
	}

	public function run()
	{
		// вывод кнопки
		echo CHtml::link('Записаться', '#', array(
			'class'=>'-button -button-green',
			'data-target'=>'#modalForm',
			'data-toggle'=>'modal',
			'data-remote' => $this->_popupUrl,
		));
	}
} 