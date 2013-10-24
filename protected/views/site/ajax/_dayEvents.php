<?php
/**
 * @var $hall Hall
 * @var $events array
 * @var $services array
 * @var $halls array
 */
if (empty($events)) {
	echo CHtml::tag('p', array('class'=>'warning-empty'), 'К сожалению, в этот день нет занятий. Попробуйте выбрать другой день!');
	return;
}

foreach ($halls as $hall) {
	$tmp = '';
	$hasEvents = false; // Наличие событий в принципе

	$tmp .= CHtml::tag('div', array('class'=>'text-center'), $hall->name);
	$tmp .= CHtml::openTag('div', array('class'=>'row timeline-row'));

	/** @var $event Event */
	foreach ($events as $event) {
		if ($event->hall_id == $hall->id) {
			$hasEvents = true;
			$htmlOptions = array('data-event'=>$event->id);

			$timeStart = date('H-i', $event->start_time); $ts = date('H:i', $event->start_time); $te = date('H:i', $event->end_time);
			// Продолжительность в минутах
			$eventTime = ($event->end_time - $event->start_time) / 60;

			if ($eventTime < 60) {
				$eventTime = 60;
			}

			$colorClass = isset($services[$event->service_id]) ?
			    'c-'.ltrim($services[$event->service_id]->color, '#') : '';

			$class = 'col-'.$eventTime.' start-'.$timeStart.' '.$colorClass;
			if ($event->is_draft == EventTemplate::DRAFT_YES) {
				$class .= ' -disabled';
			}

			$htmlOptions['class'] = $class;

			$tmp .= CHtml::openTag('div', $htmlOptions);
			$text = empty($event->direction) ? '' : $event->direction->name;
			$tmp .= CHtml::tag('span', array(), $text);
			$time = $ts.' — '.$te;
			$tmp .= CHtml::tag('span', array(), $time);
			$tmp .= CHtml::closeTag('div');
		}
	}
	$tmp .= CHtml::closeTag('div');

	$htmlOptions = array();
	if (!$hasEvents) { continue; }
	echo CHtml::tag('div', $htmlOptions, $tmp);
}
