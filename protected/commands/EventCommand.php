<?php
class EventCommand extends CConsoleCommand{

	public function run()
	{
		Yii::import('application.models.*');
		$templates = EventTemplate::model()->findAllByAttributes(array('status'=>EventTemplate::STATUS_ACTIVE, 'type'=>EventTemplate::TYPE_REGULAR));

		$time = time()+28*86400;
		$time = strtotime('TODAY', $time);

		$count = 0;
		foreach ($templates as $template) {
			if ($this->checkEvent($template, $time)) {
				Event::createEvent($template, $time);
				$count++;
			}
		}

		echo date('[Y-m-d H:i:s]', time()).' TOTAL CREATED EVENTS: '.$count."\n";
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
		$endDay = $time+86400;
		$count = Event::model()->countByAttributes(array('template_id'=>$template->id), 'start_time>:time and start_time<:end', array(':time'=>$time, ':end'=>$endDay));
		return $count == 0;
	}
}