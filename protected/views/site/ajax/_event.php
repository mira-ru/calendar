<?php
/**
 * @var $event Event
 * @var $day integer
 * @var $model
 */
?>
<strong><?php
	echo $event->direction->checkShowLink()
	    ? CHtml::link(
		    $event->direction->name,
		    $this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$day, 'popup'=>'a='.$event->direction_id)),
		    array('data-remote'=>$this->createUrl('/site/axPopup', array('item'=>$event->direction_id, 'type'=>'a')),
			    'data-action-id'=>$event->direction_id,
			    'data-toggle'=>'modal',
			    'data-target'=>'#modal',
			    'class'=>'green'
		    )
	    )
	    : $event->direction->name;
	if (!Yii::app()->getUser()->getIsGuest()) {
		echo CHtml::link('',
			$this->createUrl('/admin/event/update', array('id'=>$event->id)),
			array('class'=>'pencil', 'target'=>'_blank')
		);;
	}
	?></strong>
<?php
$users = $event->getUsers();
if (!empty($users)) {
	echo CHtml::openTag('span');
	echo CHtml::tag('i', array(), 'Мастер:');
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
			    $this->createUrl('/site/index', array('class_id'=>$model::MODEL_TYPE, 'id'=>$model->id, 'time'=>$day, 'popup'=>'m='.$user->id)),
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


	echo CHtml::closeTag('span');
}
?>
<span><i>Место:</i><?php echo $event->hall->name; ?></span>
<?php $dow = date('w', $event->start_time); ?>
<span><i>Время:</i><?php echo DateMap::$smallDayMap[$dow].', '.date('H:i', $event->start_time).'-'.date('H:i', $event->end_time); ?></span>
<div><?php echo $event->desc; ?></div>
<?php
if (Config::getViewType($model) == Config::VIEW_DAY) {
	echo CHtml::link(
		'Расписание на неделю',
		$this->createUrl('/site/index', array('class_id'=>Direction::MODEL_TYPE, 'id'=>$event->direction_id, 'time'=>$day)),
		array(
			'class'=>'green'
		)
	);
}
?>

<?php $this->widget('application.components.widgets.SignUpButtonWidget', array('event'=>$event)); ?>