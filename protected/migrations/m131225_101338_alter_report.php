<?php

class m131225_101338_alter_report extends CDbMigration
{
	public function up()
	{
		$this->addColumn('report', 'user', 'tinyint(1) after id');
		$this->createIndex('idx_user', 'report', 'user');
	}

	public function down()
	{
		$this->dropColumn('report', 'user');
	}
}