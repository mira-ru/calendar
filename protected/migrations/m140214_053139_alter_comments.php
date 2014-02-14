<?php

class m140214_053139_alter_comments extends CDbMigration
{
	public function up()
	{
		$this->addColumn('user', 'comment', 'VARCHAR(5000) NOT NULL DEFAULT "" after `desc`');
		$this->addColumn('service', 'comment', 'VARCHAR(5000) NOT NULL DEFAULT "" after `color`');
		$this->addColumn('direction', 'comment', 'VARCHAR(5000) NOT NULL DEFAULT "" after `price`');
	}

	public function down()
	{
		$this->dropColumn('user', 'comment');
		$this->dropColumn('service', 'comment');
		$this->dropColumn('direction', 'comment');
	}

}