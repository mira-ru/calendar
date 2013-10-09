<?php
/**
 * @var $event Event
 * @var $centerId integer
 * @var $day integer
 * @var $directionId integer
 * @var $serviceId integer
 */
?>
<strong><?php
	echo !$event->direction->checkShowLink()
	    ? CHtml::link(
		    $event->direction->name,
		    $this->createUrl('/site/index', array('center_id'=>$centerId, 'service_id'=>$serviceId, 'direction_id'=>$directionId, 'time'=>$day, 'popup'=>'a='.$event->direction_id)),
		    array('data-remote'=>$this->createUrl('/site/axPopup', array('item'=>$event->direction_id, 'type'=>'a')),
			    'data-eventid'=>$event->id,
			    'data-toggle'=>'modal',
			    'data-target'=>'#modal',
			    'class'=>'green'
		    )
	    )
	    : $event->direction->name, $event->direction->url;
	if (!Yii::app()->getUser()->getIsGuest()) {
		echo CHtml::link('',
			$this->createUrl('/admin/event/update', array('id'=>$event->id)),
			array('class'=>'pencil', 'target'=>'_blank')
		);;
	}
	?></strong>
<span><i>Мастер:</i><?php
	echo !$event->user->checkShowLink()
	    ? CHtml::link(
		    $event->user->name,
		    $this->createUrl('/site/index', array('center_id'=>$centerId, 'service_id'=>$serviceId, 'direction_id'=>$directionId, 'time'=>$day, 'popup'=>'a='.$event->direction_id)),
		    array(
			    'data-remote'=>$this->createUrl('/site/axPopup', array('item'=>$event->user_id, 'type'=>'m')),
			    'data-masterid'=>$event->user->id,
			    'data-toggle'=>'modal',
			    'data-target'=>'#modal',
			    'class'=>'green'
		    )
	    )
	    : $event->user->name;
	?></span>
<span><i>Зал:</i><?php echo $event->hall->name; ?></span>
<?php $dow = date('w', $event->start_time); ?>
<span><i>Время:</i><?php echo DateMap::$smallDayMap[$dow].', '.date('H:i', $event->start_time).'-'.date('H:i', $event->end_time); ?></span>
<div><?php echo $event->desc; ?></div>
