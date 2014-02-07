<?php

class m140207_084952_alter_sign_up extends CDbMigration
{
	public function up()
	{
		$this->addColumn('sign_up', 'comment', 'varchar(5000) after `email`');
		$this->addColumn('sign_up', 'is_first', 'tinyint(1) after `email`');
		$this->addColumn('sign_up', 'is_need_consult', 'tinyint(1) after `email`');
	}

	public function down()
	{
		$this->dropColumn('sign_up', 'comment');
		$this->dropColumn('sign_up', 'is_first');
		$this->dropColumn('sign_up', 'is_need_consult');
	}
}