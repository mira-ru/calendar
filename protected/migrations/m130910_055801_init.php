<?php

class m130910_055801_init extends CDbMigration
{
	public function up()
	{
//		$this->createTable('event', array(
//			'id'          => 'pk',
//			'status'      => 'TINYINT(1) NOT NULL DEFAULT 0',
//			'type' => 'TINYINT(1) NOT NULL DEFAULT 0',
//			'user_id'     => 'INT(11) NOT NULL',
//			'center_id' => 'INT(11) NOT NULL',
//			'hall_id' => 'INT(11) NOT NULL',
//			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
//			'day_of_week' => 'TINYINT(1) NOT NULL DEFAULT 1',
//			'create_time' => 'INT(11) NOT NULL DEFAULT 0',
//			'update_time' => 'INT(11) NOT NULL DEFAULT 0',
//			'start_time' => 'INT(11) NOT NULL DEFAULT 0',
//			'end_time' => 'INT(11) NOT NULL DEFAULT 0',
//		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$this->createTable('user', array(
			'id'          => 'pk',
			'status'      => 'TINYINT(1) NOT NULL DEFAULT 0',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'create_time' => 'INT(11) NOT NULL DEFAULT 0',
			'update_time' => 'INT(11) NOT NULL DEFAULT 0',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$this->createTable('center', array(
			'id'          => 'pk',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'create_time' => 'INT(11) NOT NULL DEFAULT 0',
			'update_time' => 'INT(11) NOT NULL DEFAULT 0',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$this->createTable('service', array(
			'id'          => 'pk',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'create_time' => 'INT(11) NOT NULL DEFAULT 0',
			'update_time' => 'INT(11) NOT NULL DEFAULT 0',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$this->createTable('hall', array(
			'id'          => 'pk',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'create_time' => 'INT(11) NOT NULL DEFAULT 0',
			'update_time' => 'INT(11) NOT NULL DEFAULT 0',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');
	}

	public function down()
	{
		$this->dropTable('user');
		$this->dropTable('center');
		$this->dropTable('service');
		$this->dropTable('hall');
	}

}