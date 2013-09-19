<?php
class EventCommand extends CConsoleCommand{

	public function run()
	{
		Yii::import('application.models.*');
		$templates = EventTemplate::model()->findAllByAttributes(array('status'=>EventTemplate::STATUS_ACTIVE, 'type'=>EventTemplate::TYPE_REGULAR));

		$time = time();

		$count = 0;
		foreach ($templates as $template) {
			if ($this->checkEvent($template, $time)) {
				$this->createEvent($template, $time);
				$count++;
			}
		}

		echo date('[Y-m-d H:i:s]', $time).' TOTAL CREATED EVENTS: '.$count."\n";
	}

	/**
	 * Проверка необходимости создания события
	 * @param $template EventTemplate
	 */
	private function checkEvent($template, $time)
	{
		$dayOfWeek = date('w', $time);
		if ($dayOfWeek != $template->day_of_week) {
			return false;
		}
		$count = Event::model()->countByAttributes(array('template_id'=>$template->id), 'start_time>:time', array(':time'=>$time));
		return $count == 0;
	}

	/**
	 * Создание нового события (линка)
	 * @param $template EventTemplate
	 */
	private function createEvent($template, $time)
	{
		$initTime = strtotime('TODAY', $time);

		$event = new Event();
		$event->direction_id = $template->direction_id;
		$event->hall_id = $template->hall_id;
		$event->user_id = $template->user_id;
		$event->center_id = $template->center_id;
		$event->service_id = $template->service_id;
		$event->day_of_week = $template->day_of_week;
		$event->start_time = $template->start_time + $initTime;
		$event->end_time = $template->end_time + $initTime;
		$event->template_id = $template->id;

		$event->save(false);
	}
}