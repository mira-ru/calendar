<?php

class m130911_061253_events extends CDbMigration
{
	public function up()
	{
		$this->createTable('event_template', array(
			'id'          => 'pk',
			'status'      => 'TINYINT(1) NOT NULL DEFAULT 0',
			'type' => 'TINYINT(1) NOT NULL DEFAULT 1',
			'user_id'     => 'INT(11) NOT NULL',
			'center_id' => 'INT(11) NOT NULL',
			'service_id' => 'INT(11) NOT NULL',
			'hall_id' => 'INT(11) NOT NULL',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'day_of_week' => 'TINYINT(1) NOT NULL DEFAULT 0',
			'init_time' => 'INT(11) NOT NULL DEFAULT 0',
			'start_time' => 'INT(11) NOT NULL DEFAULT 0',
			'end_time' => 'INT(11) NOT NULL DEFAULT 0',
			'create_time' => 'INT(11) NOT NULL DEFAULT 0',
			'update_time' => 'INT(11) NOT NULL DEFAULT 0',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');


		$this->createTable('event', array(
			'id'          => 'pk',
			'template_id' => 'INT(11) NOT NULL',
			'hall_id' => 'INT(11) NOT NULL',
			'user_id'     => 'INT(11) NOT NULL',
			'center_id' => 'INT(11) NOT NULL',
			'service_id' => 'INT(11) NOT NULL',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'day_of_week' => 'TINYINT(1) NOT NULL DEFAULT 1',
			'start_time' => 'INT(11) NOT NULL DEFAULT 0',
			'end_time' => 'INT(11) NOT NULL DEFAULT 0',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$this->createIndex('template', 'event', 'template_id');
	}

	public function down()
	{
		$this->dropTable('event');
		$this->dropTable('event_template');
	}

}