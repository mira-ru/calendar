<?php

class m131024_065015_alter_direction extends CDbMigration
{
	public function up()
	{
		Yii::import('application.models.*');
		$this->addColumn('direction', 'center_id', 'INT(11) NOT NULL after service_id');
		$sql = 'SELECT t.id, s.center_id FROM direction as t '
		    	.'INNER JOIN service as s ON t.service_id=s.id';

		$data = Yii::app()->db->createCommand($sql)->queryAll();

		foreach ($data as $item) {
			Direction::model()->updateByPk($item['id'], array('center_id'=>$item['center_id']));
		}
	}

	public function down()
	{
		$this->dropColumn('direction', 'center_id');
	}

}