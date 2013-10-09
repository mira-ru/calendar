<?php
/**
 * @var $checkedTime integer
 * @var $activeDays integer
 */

$daysOfMonth = date('t', $checkedTime);
$dayNumber = date('j', $checkedTime);
$currentMonth = DateMap::currentMonth($checkedTime);

for ($n=1; $n<=$daysOfMonth; $n++) {
	echo CHtml::openTag('td');

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

	echo CHtml::openTag('span', $htmlOptions);

	$htmlOptions = array(
		'data-weekday'=>DateMap::$smallDayMap[$dow],
		'data-day'=>$dayTime,
	);
	if ($dow == 0 || $dow == 6) {
		$htmlOptions['class'] = 'weekend';
	}

	echo CHtml::tag('i', $htmlOptions, $n);

	echo CHtml::closeTag('span');
	echo CHtml::closeTag('td');
}