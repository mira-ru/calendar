<?php
/**
 * @var $events array
 * @var $services array
 * @var $event Event
 * @var $checkedTime integer
 */
if (empty($events)) {
	echo CHtml::tag('p', array('class'=>'warning-empty'), 'К сожалению, в этот день нет занятий. Попробуйте выбрать другой день!');
	return;
}

$weeksData = array();
$dow = date('w', $checkedTime);
foreach ($events as $event) {
	$tmp = '';
	$htmlOptions = array('data-sub'=>$event->direction_id, 'data-event'=>$event->id, 'data-sid'=>$event->service_id);

	$colorClass = isset($services[$event->service_id]) ?
	    'c-'.ltrim($services[$event->service_id]->color, '#') : '';
	$htmlOptions['class'] = 'col-150 '.$colorClass;

	$content = CHtml::tag('span', array(), (date('H:i', $event->start_time).' — '.date('H:i', $event->end_time)) );
	$content .= ($event->user->checkShowLink())
	    ? CHtml::link($event->user->name)
	    : CHtml::tag('span', array(), $event->user->name);

	$tmp .= CHtml::tag('div', $htmlOptions, $content);

	if (isset($weeksData[$event->day_of_week])) {
		$weeksData[$event->day_of_week] .= $tmp;
	} else {
		$weeksData[$event->day_of_week] = $tmp;
	}
}

for ($i = 1; $i<7; $i++) {
	$class = 'row timeline-row';
	if ($i==$dow) { $class .= ' current'; }
	echo CHtml::openTag('div', array('class'=>$class));
	if (isset($weeksData[$i])) {
		echo $weeksData[$i];
	}
	echo CHtml::closeTag('div');
}

$class = 'row timeline-row';
if (0==$dow) { $class .= ' current'; }
echo CHtml::openTag('div', array('class'=>$class));
if (isset($weeksData[0])) {
	echo $weeksData[0];
}
echo CHtml::closeTag('div');