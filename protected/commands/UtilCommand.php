<?php
class UtilCommand extends CConsoleCommand
{
	public function actionResetDesc()
	{
		Yii::import('application.models.*');

		$purifier = new CHtmlPurifier();
		$options = array(
			'HTML.AllowedElements' => array(
				'em' => true,
				'a' => true,
				'strong' => true,
				'br' => true,
				'p' => true,
			),
			'HTML.AllowedAttributes' => array(
				'a.href' => true, 'a.title' => true,
			),
		);

		$purifier->setOptions($options);


		$sql = 'SELECT id, `desc` FROM user';
		$users = Yii::app()->db->createCommand($sql)->queryAll();

		/** @var $transaction CDbTransaction */
		$transaction = Yii::app()->db->beginTransaction();
		try {
			foreach ($users as $user) {
				if (empty($user['desc'])) { continue; }

				$desc = $purifier->purify($user['desc']);
				User::model()->updateByPk($user['id'], array('desc'=>$desc));
			}
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
			print_r('error users');
		}

		/// events

		$sql = 'SELECT id, `desc` FROM event';
		$events = Yii::app()->db->createCommand($sql)->queryAll();

		/** @var $transaction CDbTransaction */
		$transaction = Yii::app()->db->beginTransaction();
		try {
			foreach ($events as $event) {
				if (empty($event['desc'])) { continue; }

				$desc = $purifier->purify($event['desc']);
				Event::model()->updateByPk($event['id'], array('desc'=>$desc));
			}
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
			print_r('error events');
		}

		// event templates
		$sql = 'SELECT id, `desc` FROM event_template';
		$events = Yii::app()->db->createCommand($sql)->queryAll();

		/** @var $transaction CDbTransaction */
		$transaction = Yii::app()->db->beginTransaction();
		try {
			foreach ($events as $event) {
				if (empty($event['desc'])) { continue; }

				$desc = $purifier->purify($event['desc']);
				EventTemplate::model()->updateByPk($event['id'], array('desc'=>$desc));
			}
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
			print_r('error templates');
		}

		// direction

		$sql = 'SELECT id, `desc`, price FROM direction';
		$directions = Yii::app()->db->createCommand($sql)->queryAll();
		print_r(count($directions));

		/** @var $transaction CDbTransaction */
		$transaction = Yii::app()->db->beginTransaction();
		try {
			foreach ($directions as $direction) {

				$desc = $purifier->purify($direction['desc']);
				$price = $purifier->purify($direction['price']);
				Direction::model()->updateByPk($direction['id'], array('desc'=>$desc, 'price'=>$price));
			}
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
			print_r('error directions');
		}


	}

	public function resetEvents()
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