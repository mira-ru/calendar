<?php
class EventCommand extends CConsoleCommand{

	public function actionRun()
	{
		Yii::import('application.models.*');
		$templates = EventTemplate::model()->findAllByAttributes(array('status'=>EventTemplate::STATUS_ACTIVE, 'type'=>EventTemplate::TYPE_REGULAR));

		$time = time()+12*DateMap::TIME_WEEK;
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

	public function actionCreateLinks()
	{
		Yii::import('application.models.*');
		$templates = EventTemplate::model()->findAllByAttributes(array('status'=>EventTemplate::STATUS_ACTIVE, 'type'=>EventTemplate::TYPE_REGULAR));

		$time = time()+3*DateMap::TIME_WEEK;
		$time = strtotime('TODAY', $time);

		$count = 0;
		/** @var $template EventTemplate */
		foreach ($templates as $template) {
			$tmpTime = $time;
			for ($i=0; $i<12*7; $i++) {
				$dow = date('w', $tmpTime);
				if ($template->day_of_week != $dow) {
					$tmpTime += DateMap::TIME_DAY;
					continue;
				}
				if ($this->checkEvent($template, $tmpTime)) {
					Event::createEvent($template, $tmpTime);
					$count++;
				}
				$tmpTime += DateMap::TIME_DAY;
			}
			echo date('[Y-m-d H:i:s]', time()).' TOTAL CREATED EVENTS: '.$count."\n";
			$count=0;
		}
	}
}