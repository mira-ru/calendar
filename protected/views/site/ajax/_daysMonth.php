<?php
/**
 * @var $currentTime integer
 * @var $activeDays integer
 */

$daysOfMonth = date('t', $currentTime);
$dayNumber = date('j', $currentTime);
$currentMonth = DateMap::currentMonth($currentTime);

for ($n=1; $n<=$daysOfMonth; $n++) {
	$htmlOptions = array('class'=>'');
	if ($n == $dayNumber) {
		$htmlOptions['class'] = 'current';
	}
	$dayTime = $currentMonth + ($n-1)*86400;
	$dow = date('w', $dayTime);

	// нет событий в дне
	if (empty($activeDays[$dayTime])) {
		$htmlOptions['class'] .= ' disabled';
	}

	echo CHtml::openTag('li', $htmlOptions);

	echo CHtml::openTag('span');

	$htmlOptions = array(
		'data-weekday'=>DateMap::$smallDayMap[$dow],
		'data-day'=>$dayTime,
	);
	if ($dow == 0 || $dow == 6) {
		$htmlOptions['class'] = 'weekend';
	}

	echo CHtml::tag('i', $htmlOptions, $n);

	echo CHtml::closeTag('span');
	echo CHtml::closeTag('li')."\n";
}