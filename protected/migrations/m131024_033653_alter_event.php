<?php

class m131024_033653_alter_event extends CDbMigration
{
	public function up()
	{
		$this->addColumn('event', 'is_draft', 'TINYINT(1) NOT NULL DEFAULT 0 after service_id');
		$this->addColumn('event_template', 'is_draft', 'TINYINT(1) NOT NULL DEFAULT 0 after direction_id');
		$this->addColumn('event_template', 'comment', 'VARCHAR(5000) NOT NULL DEFAULT "" after `desc`');
	}

	public function down()
	{
		$this->dropColumn('event', 'is_draft');
		$this->dropColumn('event_template', 'is_draft');
		$this->dropColumn('event_template', 'comment');
	}


}