<?php

class m131002_064355_image extends CDbMigration
{
	public function up()
	{
		$this->createTable('image', array(
			'id'          => 'pk',
			'status'      => 'TINYINT(1) NOT NULL DEFAULT 0',
			'name'       => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'ext'       => 'VARCHAR(7) NOT NULL DEFAULT ""',
			'desc'	=> 'VARCHAR(1024) NOT NULL DEFAULT ""',
			'create_time' => 'INT(11) NOT NULL DEFAULT 0',
			'update_time' => 'INT(11) NOT NULL DEFAULT 0',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');
	}

	public function down()
	{
		$this->dropTable('image');
	}
}