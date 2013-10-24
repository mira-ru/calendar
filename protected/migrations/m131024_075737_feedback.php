<?php

class m131024_075737_feedback extends CDbMigration
{
	public function up()
	{
		$this->createTable('feedback', array(
			'id'          => 'pk',
			'status' => 'TINYINT(1) NOT NULL DEFAULT 0',
			'name' => 'VARCHAR(255) NOT NULL DEFAULT ""',
			'text'     => 'VARCHAR(2000) NOT NULL DEFAULT ""',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');
	}

	public function down()
	{
		$this->dropTable('feedback');
	}
}