<?php
/**
 * @var $events array
 * @var $services array
 * @var $event Event
 * @var $currentTime integer
 */
if (empty($events)) {
	echo CHtml::tag('p', array('class'=>'warning-empty'), 'К сожалению, в этот день нет занятий. Попробуйте выбрать другой день!');
	return;
}
// массив начал занятий на неделе
$timePoints = array();
$dayTimes = array();

// раскладываем события
foreach ($events as $event) {
	$dow = $event->day_of_week;
	$time = DateMap::timeOfDay($event->start_time);

	$dayTimes[$dow][$time][] = $event;

	$cnt = count( $dayTimes[$dow][$time] );
	if ( empty($timePoints[$time]) || $timePoints[$time] < $cnt) {
		$timePoints[$time] = $cnt;
	}
}
// сортируем по времени
ksort($timePoints, SORT_NUMERIC);

$emptyBlock = CHtml::tag('div', array('class'=>'col-150 empty'), '');

$dow = date('w', $currentTime);


$renderItem = function($event, $services) {
	$tmp = '';
	$htmlOptions = array('data-event'=>$event->id);

	$colorClass = isset($services[$event->service_id]) ?
	    'c-'.ltrim($services[$event->service_id]->color, '#') : '';
	$htmlOptions['class'] = 'col-150 '.$colorClass;

	$content = CHtml::tag('span', array(), (date('H:i', $event->start_time).' — '.date('H:i', $event->end_time)) );

	$users = $event->getUsers();
	if (!empty($users)) {
		$userStr = '';
		foreach ($users as $user) {
			if (!empty($userStr)) {
				$userStr .= ', ';
			}
			$userStr .= $user->name;
		}
//		$userStr = StrUtil::getLimb($userStr, 50);
		$content .= CHtml::tag('span', array(), $userStr);
	}

	$tmp .= CHtml::tag('div', $htmlOptions, $content);

	return $tmp;
};

for ($i = 1; $i<7; $i++) {
	$class = 'row timeline-row';
	if ($i==$dow) { $class .= ' current'; }

	echo CHtml::openTag('div', array('class'=>$class));

	foreach ($timePoints as $pointKey=>$point) {

		for ($cnt=0; $cnt<$point; $cnt++) {
			if ( empty( $dayTimes[$i][$pointKey] )) {
				echo $emptyBlock;
				continue;
			}

			$event = array_shift($dayTimes[$i][$pointKey]);
			echo $renderItem($event, $services);
		}
	}

	echo CHtml::closeTag('div');
}

$class = 'row timeline-row';
if (0==$dow) { $class .= ' current'; }
echo CHtml::openTag('div', array('class'=>$class));

foreach ($timePoints as $pointKey=>$point) {
	for ($cnt=0; $cnt<$point; $cnt++) {
		if ( empty( $dayTimes[0][$pointKey] )) {
			echo $emptyBlock;
			continue;
		}

		$event = array_shift($dayTimes[0][$pointKey]);

		echo $renderItem($event, $services);
	}
}

echo CHtml::closeTag('div');