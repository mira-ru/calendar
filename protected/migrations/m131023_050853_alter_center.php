<?php

class m131023_050853_alter_center extends CDbMigration
{
	public function up()
	{
		$this->renameColumn('center', 'view_type', 'overview');
		$this->addColumn('center', 'detailed_view', 'TINYINT(1) NOT NULL after overview');
		$sql = 'UPDATE center SET detailed_view=3';
		Yii::app()->db->createCommand($sql)->execute();
	}

	public function down()
	{
		$this->renameColumn('center', 'overview', 'view_type');
		$this->dropColumn('center', 'detailed_view');
	}


}