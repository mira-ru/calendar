<?php
/**
 * Переиндексатор sphinx
 *
 * @author alexsh
 */
Yii::import('application.models.*');
class SphinxReindexCommand extends CConsoleCommand
{
	/**
	 * Шаг индексации для различных индексов 
	 */
	const INDEX_STEP = 1000; // Default range
	private $_prefix = '';

	public function init()
	{
		$this->_prefix = Yii::app()->sphinx->tablePrefix;
	}
	
	/**
	 * Переиндексация всех индексов 
	 */
	public function actionRun()
	{
		$this->actionFilter();
	}

	public function actionFilter()
	{
		// TODO: update sphinx to 2.1.2
		echo "====> {$this->_prefix}filter indexing <===\n";
		$start = time();
		echo 'Time start: '.date('d-M-Y H:i:s')."\n";
		$total = 0;

		try {
			/** Очистка индекса */
			$sphinxQl = 'SELECT id FROM {{filter}} LIMIT 10000';
			$data = Yii::app()->sphinx->createCommand($sphinxQl)->queryColumn();
			if (!empty($data)) {
				$sphinxDel = 'DELETE FROM {{filter}} WHERE id IN ('.implode(',', $data).')';
				Yii::app()->sphinx->createCommand($sphinxDel)->execute();
			}
			// завершение очистки


			// Center
			$sql = 'SELECT DISTINCT t.id, t.name FROM center as t '
				.'INNER JOIN event as e ON e.center_id=t.id AND e.start_time > '.$start.' AND e.start_time < '.($start + 8*DateMap::TIME_WEEK).' '
				.'WHERE t.status='.Center::STATUS_ACTIVE;
			$data = Yii::app()->db->createCommand($sql)->queryAll();

			if (!empty($data)) {
				$sphinxQl = 'REPLACE INTO {{filter}} (`id`, `name`, `type_id`) VALUES ';
				$cnt = 0;
				foreach ($data as $item) {
					if ($cnt > 0)
						$sphinxQl .= ',';
					else
						$cnt++;

					$sphinxQl .= '('.($item['id']*100 + Center::MODEL_TYPE).',\''.addslashes($item['name']).'\','.Center::MODEL_TYPE.')';
					$result = Yii::app()->sphinx->createCommand($sphinxQl)->execute();
				}
				$total += $result;
				echo "{$result} items was written \n";
			}

			// User
			$sql = 'SELECT DISTINCT t.id, t.name FROM user as t '
			    .'INNER JOIN event as e ON e.user_id=t.id AND e.start_time > '.$start.' AND e.start_time < '.($start + 8*DateMap::TIME_WEEK).' '
			    .'WHERE t.status='.User::STATUS_ACTIVE;

			$data = Yii::app()->db->createCommand($sql)->queryAll();

			if (!empty($data)) {
				$sphinxQl = 'REPLACE INTO {{filter}} (`id`, `name`, `type_id`) VALUES ';
				$cnt = 0;
				foreach ($data as $item) {
					if ($cnt > 0)
						$sphinxQl .= ',';
					else
						$cnt++;

					$sphinxQl .= '('.($item['id']*100 + User::MODEL_TYPE).',\''.addslashes($item['name']).'\','.User::MODEL_TYPE.')';
					$result = Yii::app()->sphinx->createCommand($sphinxQl)->execute();
				}
				$total += $result;
				echo "{$result} items was written \n";
			}

			// Direction
			$sql = 'SELECT DISTINCT t.id, t.name FROM direction as t '
			    .'INNER JOIN event as e ON e.direction_id=t.id AND e.start_time > '.$start.' AND e.start_time < '.($start + 8*DateMap::TIME_WEEK).' '
			    .'WHERE t.status='.Direction::STATUS_ACTIVE;
			$data = Yii::app()->db->createCommand($sql)->queryAll();

			if (!empty($data)) {
				$sphinxQl = 'REPLACE INTO {{filter}} (`id`, `name`, `type_id`) VALUES ';
				$cnt = 0;
				foreach ($data as $item) {
					if ($cnt > 0)
						$sphinxQl .= ',';
					else
						$cnt++;

					$sphinxQl .= '('.($item['id']*100 + Direction::MODEL_TYPE).',\''.addslashes($item['name']).'\','.Direction::MODEL_TYPE.')';
					$result = Yii::app()->sphinx->createCommand($sphinxQl)->execute();
				}
				$total += $result;
				echo "{$result} items was written \n";
			}

			// Hall
			$sql = 'SELECT DISTINCT t.id, t.name FROM hall as t '
			    .'INNER JOIN event as e ON e.hall_id=t.id AND e.start_time > '.$start.' AND e.start_time < '.($start + 8*DateMap::TIME_WEEK).' '
			    .'WHERE t.status='.Hall::STATUS_ACTIVE;
			$data = Yii::app()->db->createCommand($sql)->queryAll();

			if (!empty($data)) {
				$sphinxQl = 'REPLACE INTO {{filter}} (`id`, `name`, `type_id`) VALUES ';
				$cnt = 0;
				foreach ($data as $item) {
					if ($cnt > 0)
						$sphinxQl .= ',';
					else
						$cnt++;

					$sphinxQl .= '('.($item['id']*100 + Hall::MODEL_TYPE).',\''.addslashes($item['name']).'\','.Hall::MODEL_TYPE.')';
					$result = Yii::app()->sphinx->createCommand($sphinxQl)->execute();
				}
				$total += $result;
				echo "{$result} items was written \n";
			}

			// Service
			$sql = 'SELECT DISTINCT t.id, t.name FROM service as t '
			    .'INNER JOIN event as e ON e.service_id=t.id AND e.start_time > '.$start.' AND e.start_time < '.($start + 8*DateMap::TIME_WEEK).' '
			    .'WHERE t.status='.Service::STATUS_ACTIVE;
			$data = Yii::app()->db->createCommand($sql)->queryAll();

			if (!empty($data)) {
				$sphinxQl = 'REPLACE INTO {{filter}} (`id`, `name`, `type_id`) VALUES ';
				$cnt = 0;
				foreach ($data as $item) {
					if ($cnt > 0)
						$sphinxQl .= ',';
					else
						$cnt++;

					$sphinxQl .= '('.($item['id']*100 + Service::MODEL_TYPE).',\''.addslashes($item['name']).'\','.Service::MODEL_TYPE.')';
					$result = Yii::app()->sphinx->createCommand($sphinxQl)->execute();
				}
				$total += $result;
				echo "{$result} items was written \n";
			}


			echo "Total index result: {$total}\n";
		} catch (Exception $e) {
			echo $e->getTraceAsString()."\n";
		}

		echo 'Time stop: '.date('d-M-Y H:i:s')."\n";
		echo 'Total time: '.(time()-$start)."\n\n";
	}

}
