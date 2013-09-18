<?php

class m130918_040041_alter_logick extends CDbMigration
{
	public function up()
	{
		$this->addColumn('service', 'center_id', 'INT(11) NOT NULL after status');

		$this->createTable('direction', array(
			'id'          => 'pk',
			'status' => 'TINYINT(1) NOT NULL DEFAULT 0',
			'service_id' => 'INT(11) NOT NULL',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'create_time' => 'INT(11) NOT NULL',
			'update_time' => 'INT(11) NOT NULL',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');
	}

	public function down()
	{
		$this->dropColumn('service', 'center_id');
		$this->dropTable('direction');
	}

}