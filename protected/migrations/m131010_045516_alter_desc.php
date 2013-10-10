<?php

class m131010_045516_alter_desc extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('user', 'desc', 'VARCHAR(5000) NOT NULL DEFAULT ""');
		$this->alterColumn('direction', 'desc', 'VARCHAR(5000) NOT NULL DEFAULT ""');
		$this->alterColumn('event', 'desc', 'VARCHAR(5000) NOT NULL DEFAULT ""');
		$this->alterColumn('event_template', 'desc', 'VARCHAR(5000) NOT NULL DEFAULT ""');
	}

	public function down()
	{
		$this->alterColumn('user', 'desc', 'VARCHAR(2048) NOT NULL DEFAULT ""');
		$this->alterColumn('direction', 'desc', 'VARCHAR(2048) NOT NULL DEFAULT ""');
		$this->alterColumn('event', 'desc', 'VARCHAR(1024) NOT NULL DEFAULT ""');
		$this->alterColumn('event_template', 'desc', 'VARCHAR(1024) NOT NULL DEFAULT ""');
	}

}