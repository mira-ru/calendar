<?php
class SphinxUtil
{
	public function updateFilter($type, $id)
	{
		$start = time();
		if ($type == User::MODEL_TYPE) {

			$sql = 'SELECT DISTINCT t.id, t.name FROM user as t '
			    .'INNER JOIN event_user as eu ON eu.user_id=t.id '
			    .'INNER JOIN event as e ON e.template_id=eu.template_id AND e.start_time > '.$start.' AND e.start_time < '.($start + 8*DateMap::TIME_WEEK).' '
			    .'WHERE t.status='.User::STATUS_ACTIVE.' AND t.id='.intval($id);

			$item = Yii::app()->db->createCommand($sql)->queryRow();

			if (!empty($item)) {
				$sphinxQl = 'REPLACE INTO {{filter}} (`id`, `name`, `type_id`, `item_id`) VALUES ';
				$sphinxQl .= '('.($item['id']*100 + User::MODEL_TYPE).',\''.addslashes($item['name']).'\','.User::MODEL_TYPE.','.$item['id'].')';
				$result = Yii::app()->sphinx->createCommand($sphinxQl)->execute();

				return (bool)$result;
			}

			return false;
		}

		if ($type == Direction::MODEL_TYPE) {
			$sql = 'SELECT DISTINCT t.id, t.name FROM direction as t '
			    .'INNER JOIN event as e ON e.direction_id=t.id AND e.start_time > '.$start.' AND e.start_time < '.($start + 8*DateMap::TIME_WEEK).' '
			    .'WHERE t.status='.Direction::STATUS_ACTIVE.' AND t.id='.intval($id);
			$item = Yii::app()->db->createCommand($sql)->queryRow();

			if (!empty($item)) {
				$sphinxQl = 'REPLACE INTO {{filter}} (`id`, `name`, `type_id`, `item_id`) VALUES ';
				$sphinxQl .= '('.($item['id']*100 + Direction::MODEL_TYPE).',\''.addslashes($item['name']).'\','.Direction::MODEL_TYPE.','.$item['id'].')';
				$result = Yii::app()->sphinx->createCommand($sphinxQl)->execute();
				return (bool)$result;
			}
			return false;
		}

		if ($type == Hall::MODEL_TYPE) {
			$sql = 'SELECT DISTINCT t.id, t.name FROM hall as t '
			    .'INNER JOIN event as e ON e.hall_id=t.id AND e.start_time > '.$start.' AND e.start_time < '.($start + 8*DateMap::TIME_WEEK).' '
			    .'WHERE t.status='.Hall::STATUS_ACTIVE.' AND t.id='.intval($id);
			$item = Yii::app()->db->createCommand($sql)->queryRow();

			if (!empty($item)) {
				$sphinxQl = 'REPLACE INTO {{filter}} (`id`, `name`, `type_id`, `item_id`) VALUES ';
				$sphinxQl .= '('.($item['id']*100 + Hall::MODEL_TYPE).',\''.addslashes($item['name']).'\','.Hall::MODEL_TYPE.','.$item['id'].')';
				$result = Yii::app()->sphinx->createCommand($sphinxQl)->execute();
				return (bool)$result;
			}

			return false;
		}

		if ($type == Service::MODEL_TYPE) {
			$sql = 'SELECT DISTINCT t.id, t.name FROM service as t '
			    .'INNER JOIN event as e ON e.service_id=t.id AND e.start_time > '.$start.' AND e.start_time < '.($start + 8*DateMap::TIME_WEEK).' '
			    .'WHERE t.status='.Service::STATUS_ACTIVE;
			$item = Yii::app()->db->createCommand($sql)->queryAll();

			if (!empty($item)) {
				$sphinxQl = 'REPLACE INTO {{filter}} (`id`, `name`, `type_id`, `item_id`) VALUES ';
				$sphinxQl .= '('.($item['id']*100 + Service::MODEL_TYPE).',\''.addslashes($item['name']).'\','.Service::MODEL_TYPE.','.$item['id'].')';
				$result = Yii::app()->sphinx->createCommand($sphinxQl)->execute();
				return (bool)$result;
			}

			return false;
		}

		throw new CException('Invalid model type');
	}
}