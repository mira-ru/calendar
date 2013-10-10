<?php

class DateMap {
	const TIME_HOUR = 3600;
	const TIME_DAY = 86400;
	const TIME_WEEK = 604800;

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

	public static function prevMonth($timestamp)
	{
		return strtotime( date('Y-m', strtotime("last month", $timestamp)) );
	}

	public static function nextMonth($timestamp)
	{
		return strtotime( date('Y-m', strtotime("next month", $timestamp)) );
	}

	public static function currentMonth($timestamp)
	{
		return strtotime( date('Y-m', $timestamp) );
	}

	/**
	 * Timestamp текущего дня
	 * @param $timestamp
	 * @return int
	 */
	public static function currentDay($timestamp)
	{
		return strtotime('TODAY', $timestamp);
	}

	/**
	 * Timestamp понедельника текущей недели
	 * @param $timestamp
	 */
	public static function currentWeek($timestamp)
	{
		return strtotime("Monday this week", strtotime('last sunday', $timestamp));
	}

	public static function nextWeek($timestamp)
	{
		return strtotime("Monday next week", strtotime('last sunday', $timestamp));
	}

	public static function prevWeek($timestamp)
	{
		return strtotime("Monday previous week", strtotime('last sunday', $timestamp));
	}

	/**
	 * Относительное время в течении дня
	 * @param $timestamp
	 * @return int
	 */
	public static function timeOfDay($timestamp)
	{
		return ($timestamp - strtotime('TODAY', $timestamp));
	}
}