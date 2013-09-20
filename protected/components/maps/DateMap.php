<?php

class DateMap {
	public static $smallDayMap = array(
		0 => 'вс',
		1 => 'пн',
		2 => 'вт',
		3 => 'ср',
		4 => 'чт',
		5 => 'пт',
		6 => 'сб',
	);

	public static $monthMap = array(
		1 => 'Январь',
		2 => 'Февраль',
		3 => 'Март',
		4 => 'Апрель',
		5 => 'Май',
		6 => 'Июнь',
		7 => 'Июль',
		8 => 'Август',
		9 => 'Сентябрь',
		10 => 'Октябрь',
		11 => 'Ноябрь',
		12 => 'Декабрь',
	);

	public static function getPrevMonth($timestamp)
	{
		return strtotime( date('Y-m', strtotime("last month", $timestamp)) );
	}

	public static function getNextMonth($timestamp)
	{
		return strtotime( date('Y-m', strtotime("next month", $timestamp)) );
	}

	public static function currentMonth($timestamp)
	{
		return strtotime( date('Y-m', $timestamp) );
	}
}