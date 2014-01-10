<?php

class m140110_095310_userLogget extends CDbMigration
{
	public function up()
	{
		$this->addColumn('center', 'creator_id', 'integer after `id`');
		$this->addColumn('center', 'updater_id', 'integer after `creator_id`');

		$this->addColumn('direction', 'creator_id', 'integer after `id`');
		$this->addColumn('direction', 'updater_id', 'integer after `creator_id`');

		$this->addColumn('hall', 'creator_id', 'integer after `id`');
		$this->addColumn('hall', 'updater_id', 'integer after `creator_id`');

		$this->addColumn('user', 'creator_id', 'integer after `id`');
		$this->addColumn('user', 'updater_id', 'integer after `creator_id`');

		$this->addColumn('service', 'creator_id', 'integer after `id`');
		$this->addColumn('service', 'updater_id', 'integer after `creator_id`');

		$this->addColumn('event', 'creator_id', 'integer after `id`');
		$this->addColumn('event', 'updater_id', 'integer after `creator_id`');
	}

	public function down()
	{
		$this->dropColumn('center', 'creator_id');
		$this->dropColumn('center', 'updater_id');

		$this->dropColumn('direction', 'creator_id');
		$this->dropColumn('direction', 'updater_id');

		$this->dropColumn('hall', 'creator_id');
		$this->dropColumn('hall', 'updater_id');

		$this->dropColumn('user', 'creator_id');
		$this->dropColumn('user', 'updater_id');

		$this->dropColumn('service', 'creator_id');
		$this->dropColumn('service', 'updater_id');

		$this->dropColumn('event', 'creator_id');
		$this->dropColumn('event', 'updater_id');
	}
}