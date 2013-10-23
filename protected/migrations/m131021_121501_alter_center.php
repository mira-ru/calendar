<?php

class m131021_121501_alter_center extends CDbMigration
{
	public function up()
	{
		$this->addColumn('center', 'view_type', 'TINYINT(1) NOT NULL after status');
		$sql = 'UPDATE center SET view_type=1';
		Yii::app()->db->createCommand($sql)->execute();

	}

	public function down()
	{
		$this->dropColumn('center', 'view_type');
	}

}