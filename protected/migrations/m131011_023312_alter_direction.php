<?php

class m131011_023312_alter_direction extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('direction', 'price', 'VARCHAR(2048) NOT NULL DEFAULT ""');
	}

	public function down()
	{
		$this->alterColumn('direction', 'price', 'VARCHAR(512) NOT NULL DEFAULT ""');
	}

}