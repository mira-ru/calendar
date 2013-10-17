<?php

class m131003_092356_alter_user extends CDbMigration
{
	public function up()
	{
		$this->addColumn('user', 'image_id', 'INT(11) after url');
		$this->addColumn('user', 'desc', 'VARCHAR(2048) NOT NULL DEFAULT "" after image_id');

		$this->addColumn('direction', 'image_id', 'INT(11) after url');
		$this->addColumn('direction', 'desc', 'VARCHAR(2048) NOT NULL DEFAULT "" after image_id');
		$this->addColumn('direction', 'price', 'VARCHAR(512) NOT NULL DEFAULT "" after `desc`');

		$this->addColumn('event_template', 'image_id', 'INT(11) after direction_id');
		$this->addColumn('event', 'image_id', 'INT(11) after service_id');
	}

	public function down()
	{
		$this->dropColumn('user', 'image_id');
		$this->dropColumn('user', 'desc');

		$this->dropColumn('direction', 'image_id');
		$this->dropColumn('direction', 'desc');
		$this->dropColumn('direction', 'price');

		$this->dropColumn('event_template', 'image_id');
		$this->dropColumn('event', 'image_id');
	}
}