<?php

class m130910_055801_init extends CDbMigration
{
	public function up()
	{
		$this->createTable('user', array(
			'id'          => 'pk',
			'status'      => 'TINYINT(1) NOT NULL DEFAULT 0',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'create_time' => 'INT(11) NOT NULL DEFAULT 0',
			'update_time' => 'INT(11) NOT NULL DEFAULT 0',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$this->createTable('center', array(
			'id'          => 'pk',
			'status'      => 'TINYINT(1) NOT NULL DEFAULT 0',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'create_time' => 'INT(11) NOT NULL DEFAULT 0',
			'update_time' => 'INT(11) NOT NULL DEFAULT 0',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$this->createTable('service', array(
			'id'          => 'pk',
			'status'      => 'TINYINT(1) NOT NULL DEFAULT 0',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'create_time' => 'INT(11) NOT NULL DEFAULT 0',
			'update_time' => 'INT(11) NOT NULL DEFAULT 0',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$this->createTable('hall', array(
			'id'          => 'pk',
			'status'      => 'TINYINT(1) NOT NULL DEFAULT 0',
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