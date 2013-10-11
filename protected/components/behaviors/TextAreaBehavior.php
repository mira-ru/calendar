<?php

class TextAreaBehavior extends CActiveRecordBehavior
{

	public $attributes = array();
	function __construct()
	{
	}

	public function beforeSave($event)
	{
		foreach ($this->attributes as $attribute) {
			$this->getOwner()->{$attribute} = Kavychker::baseFormat($this->getOwner()->{$attribute});
		}
	}

}
