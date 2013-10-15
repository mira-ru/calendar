<?php

class m131015_074144_create_event_user extends CDbMigration
{
	public function up()
	{
		$this->createTable('event_user', array(
			'template_id' => 'INT(11) NOT NULL',
			'user_id'     => 'INT(11) NOT NULL, PRIMARY KEY (`template_id`, `user_id`)',
		), 'ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_general_ci');

		$sql = 'SELECT id, user_id FROM event_template';
		$data = Yii::app()->db->createCommand($sql)->queryAll();

		if (!empty($data)) {
			$sql = 'INSERT INTO event_user (`template_id`, `user_id`) VALUES ';
			$cnt = 0;
			foreach ($data as $item) {
				if ($cnt > 0) {
					$sql .= ',';
				} else {
					$cnt++;
				}
				$sql .= '('.$item['id'].','.$item['user_id'].')';
			}
			Yii::app()->db->createCommand($sql)->execute();
		}

		$this->dropColumn('event_template', 'user_id');
		$this->dropColumn('event', 'user_id');
	}

	public function down()
	{
		return false;
	}


}