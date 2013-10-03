<?php

class m131002_034606_alter_center extends CDbMigration
{
	public function up()
	{
		$this->addColumn('center', 'position', 'INT(11) NOT NULL DEFAULT 0 after color');
	}

	public function down()
	{
		$this->dropColumn('center', 'position');
	}

}