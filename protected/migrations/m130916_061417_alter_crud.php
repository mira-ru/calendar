<?php

class m130916_061417_alter_crud extends CDbMigration
{
	public function up()
	{
		$this->addColumn('center', 'color', 'VARCHAR(7) NOT NULL DEFAULT "" after name');
		$this->addColumn('service', 'color', 'VARCHAR(7) NOT NULL DEFAULT "" after name');
	}

	public function down()
	{
		$this->dropColumn('center', 'color');
		$this->dropColumn('service', 'color');
	}
}