<?php
/**
 * @var $currentTime integer
 * @var $activeDays integer
 */

$firstDay = DateMap::currentWeek($currentTime);

for ($n=1; $n<=7; $n++) {
	$htmlOptions = array('class'=>'');
	$dayTime = $firstDay + ($n-1)*86400;

	if ($dayTime == $currentTime) {
		$htmlOptions['class'] = 'current';
	}

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
	$dom = date('j', $dayTime);

	echo CHtml::tag('i', $htmlOptions, $dom);

	echo CHtml::closeTag('span');
	echo CHtml::closeTag('li')."\n";
}