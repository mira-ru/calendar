<?php

class m131024_075320_alter_direction extends CDbMigration
{
	public function up()
	{
		Yii::import('application.models.*');
		$this->addColumn('direction', 'short_desc', 'VARCHAR(512) NOT NULL DEFAULT "" after `desc`');

		$sql = 'SELECT id, `desc` FROM direction';
		$data = Yii::app()->db->createCommand($sql)->queryAll();

		foreach ($data as $item) {
			$desc = StrUtil::getLimb($item['desc'], 500, '');
			if (empty($desc)) {
				continue;
			}
			Direction::model()->updateByPk($item['id'], array('short_desc'=>$desc));
		}

	}

	public function down()
	{
		$this->dropColumn('direction', 'short_desc');
	}

}