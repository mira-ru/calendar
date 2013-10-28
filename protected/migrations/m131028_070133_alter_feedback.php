<?php

class m131028_070133_alter_feedback extends CDbMigration
{
	public function up()
	{
		$this->renameColumn('feedback', 'status', 'type');
	}

	public function down()
	{
		$this->renameColumn('feedback', 'type', 'status');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}