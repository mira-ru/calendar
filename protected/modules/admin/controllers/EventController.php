<?php

class EventController extends AdminController
{

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id=null)
	{
		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();

		$id = intval($id);
		// Срабатывает при открытии страницы
		if (!empty($id) && !$request->getIsPostRequest()) {
			/** @var $event Event */
			$event = Event::model()->findByPk($id);
			if ($event===null) {
				throw new CHttpException(404);
			}
			$template = $event->getTemplate();
		} else {
			$template = new EventTemplate();
			$event = new Event();
		}

		$date = $request->getParam('date');
		$startTime = $request->getParam('start_time');
		$endTime = $request->getParam('end_time');

		if($request->getIsPostRequest())
		{
			if ( isset($_POST['EventTemplate']) && isset($_POST['Event'])) {
				$event->attributes = $_POST['Event'];
				$template->attributes = $_POST['EventTemplate'];

				// установка времени события в течении дня
				$template->start_time = strtotime($startTime) - strtotime('TODAY');
				$template->end_time = strtotime($endTime) - strtotime('TODAY');
				$template->status = EventTemplate::STATUS_ACTIVE;

				$initTime = DateMap::currentDay(strtotime($date));

				$event->start_time = $template->start_time + $initTime;
				$event->end_time = $template->end_time + $initTime;

				if ($initTime)
					$event->day_of_week = date('w', $initTime);
				else
					$event->day_of_week = -1;

				$event->file = CUploadedFile::getInstance($template, 'file');

				if ( $template->validate(array('type', 'status', 'comment')) && $event->validate() ) { // Создание событий
					// сохраняем картинку
					if ($event->file instanceof CUploadedFile) {
						$file = $template->file->getTempName();
						$fileId = Yii::app()->image->putImage($file, $template->file->getName());
						if (empty($fileId)) {
							throw new CHttpException(500);
						}

						$event->image_id = $fileId;
					}

					$template->updateFromEvent($event, $template->type, $initTime);

					$template->save(false);
					$event->template_id = $template->id;
					$event->create_time = $template->create_time;
					$event->update_time = $template->update_time;
					$event->save(false);

					$template->makeLinks();

					$url = $this->createUrl('/site/index', array(
						'class_id'=>Direction::MODEL_TYPE,
						'id'=>$event->direction_id,
						'time'=>DateMap::currentDay($event->start_time)
					));

					$this->redirect($url);
				}
			}
		}

		if (!$request->getIsPostRequest()) {
			if (!empty($id)) {
				$date = date('d.m.Y', $event->start_time);
				$startTime = date('H:i', $event->start_time);
				$endTime = date('H:i', $event->end_time);
			} else {
				$date = date('d.m.Y');
				$startTime = '7.00';
				$endTime = '8.00';
			}
		}

		$centers = Center::model()->findAllByAttributes(array('status'=>Center::STATUS_ACTIVE));
		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'center_id'=>$event->center_id));
		$directions = Direction::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'service_id'=>$event->service_id));
		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));

		$this->render('create',array(
			'template' => $template,
			'event' => $event,

			'centers' => $centers,
			'services' => $services,
			'directions' => $directions,

			'halls' => $halls,
			'date' => $date,
			'startTime' => $startTime,
			'endTime' => $endTime,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		/** @var $event Event */
		$event = Event::model()->findByPk(intval($id));
		if ($event===null)
			throw new CHttpException(404);

		$template = $event->getTemplate(); //new EventTemplate();

		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();

		$changeAll = (bool)$request->getParam('change_all', false);
		$startTime = $request->getParam('start_time');
		$endTime = $request->getParam('end_time');
		$date = $request->getParam('date');

		if ($request->getIsPostRequest()) {
			if ( isset($_POST['Event']) ) {
				$event->attributes = $_POST['Event'];
				$event->file = CUploadedFile::getInstance($event, 'file');

				$template->users = !empty($_POST['EventTemplate']['users']) ? $_POST['EventTemplate']['users'] : array();
				$template->comment = !empty($_POST['EventTemplate']['comment']) ? $_POST['EventTemplate']['comment'] : '';
				// FIXME: сделать нормальную проверку пересечений. Отваливалось обновление шаблона на валидации.
				$template->forceSave = true;
//				if (isset($_POST['EventTemplate']['forceSave'])) {
//					$template->forceSave = (bool)$_POST['EventTemplate']['forceSave'];
//				}

				$newType = !isset($_POST['EventTemplate']['type']) ? EventTemplate::TYPE_SINGLE : intval($_POST['EventTemplate']['type']);
				$hasErrors = empty( EventTemplate::$typeNames[$newType] ); // валидация типа

				$initTime = DateMap::currentDay(strtotime($date));

				// установка времени события в течении дня
				// Разница в днях при изменении даты (для корректного обновления событий)
				$dTime = $initTime - DateMap::currentDay($event->start_time);


				$event->start_time = strtotime($startTime) - strtotime('TODAY') + $initTime;
				$event->end_time = strtotime($endTime) - strtotime('TODAY') + $initTime;

				if ($initTime)
					$event->day_of_week = date('w', $initTime);
				else
					$event->day_of_week = -1;

				if ($event->validate() && !$hasErrors) {
					$template->save(false); // Применение свойтв к шаблону (не привязаннных к событиям)
					$event->create_time = $template->create_time;
					$event->update_time = $template->update_time;

					// сохраняем картинку
					if ($event->file instanceof CUploadedFile) {
						$file = $event->file->getTempName();
						$fileId = Yii::app()->image->putImage($file, $event->file->getName());
						if (empty($fileId)) {
							throw new CHttpException(500);
						}

						$event->image_id = $fileId;
					}

					$event->save(false);

					// осталось одиночное событие или регулярное и изменяем только текущее
					if (($template->type==EventTemplate::TYPE_SINGLE && $newType==EventTemplate::TYPE_SINGLE)) {
						// обновляем шаблон
						$template->status = EventTemplate::STATUS_ACTIVE; // ?
						$event->updateYoungEvents($template, $dTime);
					} elseif (($template->type==EventTemplate::TYPE_REGULAR && $newType==EventTemplate::TYPE_REGULAR && !$changeAll)) {
						// ok
					} elseif ($template->type==EventTemplate::TYPE_REGULAR && $newType==EventTemplate::TYPE_SINGLE) {
						// Сменили тип на одиночное событие, прибиваем младшие копии события

						// обновляем шаблон
						$template->status = EventTemplate::STATUS_ACTIVE;
						$template->type = EventTemplate::TYPE_SINGLE;
						$template->init_time = $initTime;
						$template->save(false);
						$event->removeYoungEvents();

					} elseif ($template->type==EventTemplate::TYPE_SINGLE && $newType==EventTemplate::TYPE_REGULAR) {
						// Событие стало регулярным
						$template->status = EventTemplate::STATUS_ACTIVE;
						$template->type = EventTemplate::TYPE_REGULAR;
						$template->init_time = $initTime;
						// Сохраняем и создаем линки шаблона
						if ( $template->validateEventsPeriod() ) {
							$template->save(false);
							$template->makeLinks();
						}

					} elseif ($template->type==EventTemplate::TYPE_REGULAR && $newType==EventTemplate::TYPE_REGULAR && $changeAll) {
						// Обновляем все события

						// обновляем шаблон
						$template->status = EventTemplate::STATUS_ACTIVE;
						$event->updateYoungEvents($template, $dTime);
					} else {
						throw new CHttpException(500, 'Invalid action');
					}

					$url = $this->createUrl('index', array('Event[template_id]'=>$event->template_id, 'date_from'=>date('d.m.Y', $event->start_time )));

					$this->redirect($url);
				}
			}
		}

		if (!$request->getIsPostRequest()) {
			$date = date('d.m.Y', $event->start_time);
			$startTime = date('H.i', $event->start_time);
			$endTime = date('H.i', $event->end_time);
			$changeAll = true;
		}

		$centers = Center::model()->findAllByAttributes(array('status'=>Center::STATUS_ACTIVE));
		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));
		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'center_id'=>$event->center_id));
		$directions = Direction::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'service_id'=>$event->service_id));


		$this->render('update',array(
			'event'=>$event,
			'template'=>$template,

			'centers' => $centers,
			'services' => $services,
			'halls' => $halls,
			'directions' => $directions,

			'date' => $date,
			'changeAll' => $changeAll,
			'startTime' => $startTime,
			'endTime' => $endTime,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		/** @var $event Event */
		$event = Event::model()->findByPk(intval($id));
		if ($event===null)
			throw new CHttpException(404);

		$template = $event->getTemplate();
		if ($template->type == EventTemplate::TYPE_SINGLE) {
			$event->delete();
			$template->delete();
		} elseif ($template->type == EventTemplate::TYPE_REGULAR) {
			/**
			 * Для регулярных - проверяем число линков, ставин неактивность шаблону, если дропнули все линки
			 */
			$count = Event::model()->countByAttributes(array('template_id'=>$event->template_id));
			$event->delete();
			if ($count <= 1) {
				$template->status = EventTemplate::STATUS_DISABLED;
				$template->save(false);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax'])) {
			$url = isset($_POST['returnUrl']) ? $_POST['returnUrl'] : Yii::app()->getUser()->getReturnUrl(array('index'));
			$this->redirect($url);
		}
	}

	/**
	 * Удаление всех связанных событий после текущего
	 * (и отключение создания копий регулярного)
	 * @param $id
	 * @throws CHttpException
	 */
	public function actionDeleteall($id)
	{
		/** @var $event Event */
		$event = Event::model()->findByPk(intval($id));
		if ($event===null)
			throw new CHttpException(404);

		$template = $event->getTemplate();
		if ($template->type == EventTemplate::TYPE_SINGLE) {
			$event->delete();
			$template->status = EventTemplate::STATUS_DISABLED;
			$template->save(false);
		} elseif ($template->type == EventTemplate::TYPE_REGULAR) {
			$event->removeYoungEvents();
			$event->delete();
			$template->status = EventTemplate::STATUS_DISABLED;
			$template->save(false);
		}

		$this->redirect(
			Yii::app()->getUser()->getReturnUrl(array('index'))
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		Yii::app()->getUser()->setReturnUrl(Yii::app()->request->getRequestUri());

		$model=new Event('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];


		$centers = Center::model()->findAllByAttributes(array('status'=>Center::STATUS_ACTIVE));
		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE));
		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));

		$this->render('index',array(
			'model'=>$model,

			'centers' => $centers,
			'services' => $services,
			'halls' => $halls,
		));
	}

}
