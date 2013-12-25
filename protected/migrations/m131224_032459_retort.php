<?php

class m131224_032459_retort extends CDbMigration
{
	public function up()
	{
		$this->createTable('report', array(
			'id'=>'pk',
			'model'=>'tinyint(1) not null',
			'model_id'=>'integer not null',
			'operation'=>'tinyint(1) not null',
			'field'=>'string',
			'old_value'=>'varchar(3000)',
			'new_value'=>'varchar(3000)',
			'create_time'=>'integer not null',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$this->createIndex('idx_model', 'report', 'model');
		$this->createIndex('idx_model_id', 'report', 'model_id');
		$this->createIndex('idx_field', 'report', 'field');
		$this->createIndex('idx_create_time', 'report', 'create_time');
	}

	public function down()
	{
		$this->dropTable('report');
	}
}