<?php
/**
 * @var $event Event
 */
?>
<strong><?php
	echo empty($event->direction->url) ? $event->direction->name : CHtml::link($event->direction->name, $event->direction->url);
	if (!Yii::app()->getUser()->getIsGuest()) {
		echo CHtml::link('',
			$this->createUrl('/admin/event/update', array('id'=>$event->id)),
			array('class'=>'pencil', 'target'=>'_blank')
		);;
	}
	?></strong>
<span><i>Мастер:</i><?php echo empty($event->user->url) ? $event->user->name : CHtml::link($event->user->name, $event->user->url); ?></span>
<span><i>Зал:</i><?php echo $event->hall->name; ?></span>
<?php $dow = date('w', $event->start_time); ?>
<span><i>Время:</i><?php echo DateMap::$smallDayMap[$dow].', '.date('H:i', $event->start_time).'-'.date('H:i', $event->end_time); ?></span>
<div><?php echo $event->desc; ?></div>