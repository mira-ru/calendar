<?php
/**
 * @var $event Event
 */
?>
<strong><?php echo $event->direction->name; ?></strong>
<span><i>Мастер:</i><?php echo $event->user->name; ?></span>
<span><i>Зал:</i><?php echo $event->hall->name; ?></span>
<?php $dow = date('w', $event->start_time); ?>
<span><i>Время:</i><?php echo DateMap::$smallDayMap[$dow].', '.date('H:i', $event->start_time).'-'.date('H:i', $event->end_time); ?></span>