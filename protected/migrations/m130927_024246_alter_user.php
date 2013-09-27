<?php

class m130927_024246_alter_user extends CDbMigration
{
	public function up()
	{
		$this->addColumn('user', 'url', 'VARCHAR(512) NOT NULL DEFAULT "" after name');
		$this->addColumn('direction', 'url', 'VARCHAR(512) NOT NULL DEFAULT "" after name');

		$this->addColumn('event', 'desc', 'VARCHAR(1024) NOT NULL DEFAULT "" after name');
		$this->addColumn('event_template', 'desc', 'VARCHAR(1024) NOT NULL DEFAULT "" after name');
	}

	public function down()
	{
		$this->dropColumn('user', 'url');
		$this->dropColumn('direction', 'url');

		$this->dropColumn('event', 'desc');
		$this->dropColumn('event_template', 'desc');
	}

}