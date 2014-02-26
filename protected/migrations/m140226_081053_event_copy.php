<?php

class m140226_081053_event_copy extends CDbMigration
{
	public function up()
	{
		Yii::import('application.models.*');

		$oldCenter = 7;
		$newCenter = 4;

		/** @var $services Service[] */
		$services = Service::model()->findAllByAttributes(array('center_id'=>$oldCenter));
		$transaction = Yii::app()->db->beginTransaction();
		try {
			foreach ($services as $service) {
				$oldServiceId = $service->id;
				$service->id = null;
				$service->center_id = $newCenter;
				$service->setIsNewRecord(true);

				$newService = $this->insert($service);
				if ( $newService === null ) {
					throw new CException('Invalid service create');
				}

				/** @var $directions Direction[] */
				$directions = Direction::model()->findAllByAttributes(array('service_id'=>$oldServiceId));
				foreach ($directions as $direction) {
					$oldDirectionId = $direction->id;
					$direction->service_id = $newService->id;
					$direction->center_id = $newCenter;
					$direction->id = null;

					$newDirection = $this->insert($direction);
					if ($newDirection===null) {
						throw new CException('Invalid direction create');
					}

					/** @var $templates EventTemplate[] */
					$templates = EventTemplate::model()->findAllByAttributes(array('direction_id'=>$oldDirectionId));
					foreach ($templates as $template) {
						$oldTemplateId = $template->id;
						$template->id = null;
						$template->direction_id = $newDirection->id;
						$template->service_id = $newService->id;
						$template->center_id = $newCenter;

						$newTemplate = $this->insert($template);
						if ($newTemplate === null) {
							throw new CException('Invalid template create');
						}

						/** @var $events Event[] */
						$events = Event::model()->findAllByAttributes(array('template_id'=>$oldTemplateId));
						foreach ($events as $event) {
							$event->id = null;
							$event->template_id = $newTemplate->id;
							$event->direction_id = $newDirection->id;
							$event->service_id = $newService->id;
							$event->center_id = $newCenter;

							$newEvent = $this->insert($event);
							if ($newEvent === null) {
								throw new CException('Invalid event create');
							}
						}
					}
				}
			}
			$transaction->commit();
		} catch(Exception $e) {
			$transaction->rollback();
			print_r($e->getMessage());
			return false;
		}


	}

	public function down()
	{
		echo "m140226_081053_event_copy does not support migration down.\n";
		return false;
	}

	public function insert($model)
	{
		$builder=$model->getCommandBuilder();
		$table=$model->getMetaData()->tableSchema;
		$command=$builder->createInsertCommand($table,$model->getAttributes());
		if($command->execute())
		{
			$primaryKey=$table->primaryKey;
			if($table->sequenceName!==null)
			{
				if(is_string($primaryKey) && $model->$primaryKey===null)
					$model->$primaryKey=$builder->getLastInsertID($table);
				elseif(is_array($primaryKey))
				{
					foreach($primaryKey as $pk)
					{
						if($model->$pk===null)
						{
							$model->$pk=$builder->getLastInsertID($table);
							break;
						}
					}
				}
			}
			$model->setIsNewRecord(false);
			$model->setScenario('update');
			return $model;
		}
		return null;
	}

}