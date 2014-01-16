<?php

class m140116_032526_drop_create_admin extends CDbMigration
{
	public function up()
	{
		$this->dropTable('admin');

		$this->createTable('admin', array(
			'id'=>'pk',
			'status'=>'tinyint(1)',
			'role'=>'tinyint(1)',
			'email'=>'string',
			'username'=>'string',
			'password'=>'string',
			'create_time'=>'integer',
			'update_time'=>'integer',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$this->createIndex('idx_status', 'admin', 'status');
		$this->createIndex('idx_role', 'admin', 'role');
		$this->createIndex('idx_email', 'admin', 'email');
		$this->createIndex('idx_create_time', 'admin', 'create_time');
		$this->createIndex('idx_update_time', 'admin', 'update_time');


		$this->insert('admin', array(
			'username'=>'Елена Ширнина',
			'email'=>'shea@miracentr.ru',
			'password'=>crypt('subaru13', 'subaru13'),
			'role'=>1,
			'status'=>1,
			'create_time'=>time(),
			'update_time'=>time(),
		));

	}

	public function down()
	{
		return true;
	}

}