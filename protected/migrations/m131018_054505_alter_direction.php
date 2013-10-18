<?php

class m131018_054505_alter_direction extends CDbMigration
{
	public function up()
	{
		$this->addColumn('direction', 'photo_url', 'VARCHAR(512) NOT NULL DEFAULT "" after url');
		$this->addColumn('user', 'photo_url', 'VARCHAR(512) NOT NULL DEFAULT "" after url');
	}

	public function down()
	{
		$this->dropColumn('direction', 'photo_url');
		$this->dropColumn('user', 'photo_url');
	}
}