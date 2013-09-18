<?php

class m130918_075533_alter_event extends CDbMigration
{
	public function up()
	{
		$this->addColumn('event', 'direction_id', 'INT(11) NOT NULL after hall_id');
		$this->addColumn('event_template', 'direction_id', 'INT(11) NOT NULL after hall_id');

		$sql = 'DELETE FROM event';
		Yii::app()->db->createCommand($sql)->execute();
		$sql = 'DELETE FROM event_template';
		Yii::app()->db->createCommand($sql)->execute();
	}

	public function down()
	{
		$this->dropColumn('event', 'direction_id');
		$this->dropColumn('event_template', 'direction_id');
	}

}