<?php
/**
 * @var $model
 * @var $events array
 * @var $centerId integer
 * @var $allServices array
 *
 * @var $currentTime integer
 */
if (empty($events)) {
	echo CHtml::tag('p', array('class'=>'warning-empty'), 'К сожалению, в этот день нет занятий. Попробуйте выбрать другой день!');
	return;
}

$center = Center::model()->findByPk($centerId);
if ($center === null) { $center = new Center(); }

/** @var $event Event */
foreach ($events as $event) {
	$class = 'grid';
	if ($event->is_draft == EventTemplate::DRAFT_YES) {
		$class .= ' -disabled';
	}
	echo CHtml::openTag('div', array('class'=>$class));

	$monthNumber = date('n', $event->start_time);
	$dom = date('j', $event->start_time);
	$dow = date('w', $event->start_time);

	$tmp = $dom.'/'.$monthNumber.', '.DateMap::$smallDayMap[$dow]."\n";
	$tmp .= '<span>'.date('G', $event->start_time).'<sup>'.date('i', $event->start_time).'</sup> — '
	    .date('G', $event->end_time).'<sup>'.date('i', $event->end_time).'</sup></span>';

	echo CHtml::tag('div', array('class'=>'col-2 event-time'), $tmp);

	echo CHtml::openTag('div', array('class'=>'col-10 event-info'));

	// direction link
	echo $event->direction->checkShowLink()
	    ? CHtml::link(
		    $event->direction->name,
		    $this->createUrl('/site/index', array('class_id'=>Direction::MODEL_TYPE, 'id'=>$model->id, 'time'=>$currentTime, 'popup'=>'a='.$event->direction_id)),
		    array('data-remote'=>$this->createUrl('/site/axPopup', array('item'=>$event->direction_id, 'type'=>'a')),
			    'data-action-id'=>$event->direction_id,
			    'data-toggle'=>'modal',
			    'data-target'=>'#modal',
			    'class'=>'green'
		    )
	    )
	    : CHtml::tag('strong', array(), $event->direction->name);

	if (!Yii::app()->getUser()->getIsGuest()) {
		echo CHtml::link(''
			, $this->createUrl('/admin/event/update', array('id'=>$event->id))
			, array('class'=>'pencil', 'target'=>'_blank'));
	}

	if (!empty($event->direction->short_desc)) {
		echo CHtml::tag('p', array(), $event->direction->short_desc);
	}
	/** @var $service Service */
	$service = isset($allServices[$event->service_id]) ? $allServices[$event->service_id] : new Service();
	$colorClass = 'link-'.ltrim($service->color,'#');
	$users = $event->getUsers();
	/** @var $hall Hall */
	$hall = $event->hall;

	echo CHtml::openTag('div');
		echo CHtml::link(
			$service->name,
			$this->createUrl('/site/index', array('class_id'=>Service::MODEL_TYPE, 'id'=>$service->id, 'time'=>$currentTime)),
			array('class'=>$colorClass)
		).' / '."\n";

		echo CHtml::openTag('span');
			echo $center->name;
			if (!empty($users)) {
				echo ': ';
				$cnt = 0;
				foreach ($users as $user) {
					if ($cnt==0) {
						$cnt++;
					} else {
						echo ', ';
					}
					echo $user->checkShowLink()
					    ? CHtml::link(
						    $user->name,
						    $this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$currentTime, 'popup'=>'m='.$user->id)),
						    array(
							    'data-remote'=>$this->createUrl('/site/axPopup', array('item'=>$user->id, 'type'=>'m')),
							    'data-master-id'=>$user->id,
							    'data-toggle'=>'modal',
							    'data-target'=>'#modal',
							    'class'=>'green'
						    )
					    )
					    : $user->name;
				}
				echo ';';
			}
		echo CHtml::closeTag('span');
		echo CHtml::tag('i', array(), $hall->name);
	echo CHtml::closeTag('div');
	echo CHtml::closeTag('div');
	echo CHtml::closeTag('div');
}