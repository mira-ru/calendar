<?php

class m140204_042245_create_signup extends CDbMigration
{
	public function up()
	{
		$this->createTable('sign_up', array(
			'id' => 'pk',
			'eventId' => 'integer',
			'name' => 'string',
			'phone' => 'string',
			'email' => 'string',
			'create_time' => 'integer',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');
	}

	public function down()
	{
		$this->dropTable('sign_up');
	}
}