<?php
/**
 * @var $hall Hall
 * @var $events array
 * @var $services array
 * @var $halls array
 */

$totalHasEvents = false;
//echo CHtml::openTag('div');
foreach ($halls as $hall) {
	$tmp = '';
	$hasEvents = false; // Наличие событий в принципе
	$hasEnEvents = false; // Наличие отображаемых событий

	$tmp .= CHtml::tag('div', array('class'=>'text-center'), $hall->name);
	$tmp .= CHtml::openTag('div', array('class'=>'row timeline-row'));

	/** @var $event Event */
	foreach ($events as $event) {
		if ($event->hall_id == $hall->id) {
			$hasEvents = true;
			$htmlOptions = array('data-sub'=>$event->direction_id, 'data-event'=>$event->id);
			// Если указано направление - скрываем не подходящие элементы
			if ( !empty($directionId) && $directionId != $event->direction_id ) {
				$htmlOptions['style'] = 'display:none;';
			} else {
				$hasEnEvents = true;
			}

			// TODO: color class
			$timeStart = date('H-i', $event->start_time);
			// Продолжительность в минутах
			$eventTime = ($event->end_time - $event->start_time) / 60;

			$colorClass = isset($services[$event->service_id]) ?
			    'c-'.ltrim($services[$event->service_id]->color, '#') : '';

			$class = 'col-'.$eventTime.' start-'.$timeStart.' '.$colorClass;

			$htmlOptions['class'] = $class;

			$tmp .= CHtml::openTag('div', $htmlOptions);

			$text = empty($event->direction) ? '' : $event->direction->name;
			$tmp .= CHtml::tag('span', array(), $text);

			$tmp .= CHtml::closeTag('div');
		}
	}


	$tmp .= CHtml::closeTag('div');

	$htmlOptions = array();
	if (!$hasEvents) {
		continue;
	}
	if (!$hasEnEvents) {
		$htmlOptions['style'] = 'display:none;';
	}
	$totalHasEvents = true;
	echo CHtml::tag('div', $htmlOptions, $tmp);
}
//echo CHtml::closeTag('div');
// if ( !$totalHasEvents ) {
// 	echo CHtml::tag('p', array('class'=>'warning-empty', 'style'=>'display:block'), 'К сожалению, в этот день нет занятий. Попробуйте выбрать другой день!');
// }