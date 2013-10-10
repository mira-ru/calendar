<?php
class UtilCommand extends CConsoleCommand
{
	public function resetDesc()
	{

	}

	public function run()
	{
		Yii::import('application.models.*');

		$startTime = strtotime('07.10.2013');
		$endTime = strtotime('13.10.2013') + 86400;

//		print_r($startTime);
//		print_r($endTime);

		$sql = 'DELETE FROM event WHERE center_id=4';
		Yii::app()->db->createCommand($sql)->execute();

		$sql = 'DELETE FROM event_template WHERE (init_time < :start OR init_time >= :end) and center_id=4';
		$removed = Yii::app()->db->createCommand($sql)->bindParam(':start', $startTime)->bindParam(':end', $endTime)->execute();
		echo 'REMOVED : '.$removed."\n";


		$sql = 'SELECT * FROM event_template WHERE (init_time >= :start AND init_time < :end) and center_id=4';
		$data = Yii::app()->db->createCommand($sql)->bindParam(':start', $startTime)->bindParam(':end', $endTime)->queryAll();

		print_r(count($data))."\n";

		$templates = EventTemplate::model()->populateRecords($data);

		$time = time();

		$count = 0;

		$tmpTime = $startTime;
		while ($tmpTime < $endTime) {
			echo "go\n";
			foreach ($templates as $template) {
				if ($this->checkEvent($template, $tmpTime)) {
					$tmp = $tmpTime;
					for ($i = 1; $i <=4; $i++) {

						Event::createEvent($template, $tmp);
						$count++;
						$tmp += 86400*7;
					}
				}
			}

			$tmpTime += 86400;
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

}