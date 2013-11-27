<?php

class m131127_045959_alter_event extends CDbMigration
{
	public function up()
	{
		$this->addColumn('event', 'create_time', 'INT(11) NOT NULL');
		$this->addColumn('event', 'update_time', 'INT(11) NOT NULL');

		Yii::import('application.models.*');
		/** @var $templates EventTemplate[] */
		$templates = EventTemplate::model()->findAll(array('index'=>'id'));

		$sql = 'SELECT id, template_id FROM event';
		$data = Yii::app()->db->createCommand($sql)->queryAll();

		$transaction = Yii::app()->db->beginTransaction();
		$errors = 0;
		try {
			foreach ($data as $row) {
				if (!isset($templates[$row['template_id']])) {
					$errors++;
					continue;
				}
				$createTime = $templates[$row['template_id']]->create_time;
				$updateTime = $templates[$row['template_id']]->update_time;
				Event::model()->updateByPk($row['id'], array('create_time'=>$createTime, 'update_time'=>$updateTime));
			}
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();
			throw $e;
		}

		echo "\nERRORS: $errors\n";


	}

	public function down()
	{
		$this->dropColumn('event', 'create_time');
		$this->dropColumn('event', 'update_time');
	}
}