<?php

class EventController extends AdminController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$template = new EventTemplate();

		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();

		$date = $request->getParam('date');
		$startTime = $request->getParam('start_time');
		$endTime = $request->getParam('end_time');

		if($request->getIsPostRequest())
		{
			if (isset($_POST['EventTemplate'])) {
				$template->attributes = $_POST['EventTemplate'];
				// установка времени события в течении дня
				$template->start_time = strtotime($startTime) - strtotime('TODAY');
				$template->end_time = strtotime($endTime) - strtotime('TODAY');

				$time = strtotime($date);
				$template->init_time = $time;
				if ($time)
					$template->day_of_week = date('w', $time);
				else
					$template->day_of_week = -1;

				$template->status = EventTemplate::STATUS_ACTIVE;
			}

			if ($template->validate()) { // Создание событий
				$template->save(false);
				$this->redirect(
					Yii::app()->getUser()->getReturnUrl(array('index'))
				);
			}
		}

		if (!$request->getIsPostRequest()) {
			$date = date('d.m.Y');
			$startTime = '7.00';
			$endTime = '8.00';
		}

		$centers = Center::model()->findAllByAttributes(array('status'=>Center::STATUS_ACTIVE));
		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'center_id'=>$template->center_id));
		$directions = Direction::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE, 'service_id'=>$template->service_id));
		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));
		$this->render('create',array(
			'template' => $template,
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

		$template = new EventTemplate();

		/** @var $request CHttpRequest */
		$request = Yii::app()->getRequest();

		$changeAll = (bool)$request->getParam('change_all', false);
		$startTime = $request->getParam('start_time');
		$endTime = $request->getParam('end_time');
		$date = $request->getParam('date');

		if ($request->getIsPostRequest()) {

			$newType = !isset($_POST['EventTemplate']['type']) ? EventTemplate::TYPE_SINGLE : intval($_POST['EventTemplate']['type']);
			$hasErrors = empty( EventTemplate::$typeNames[$newType] ); // валидация типа

			if (isset($_POST['Event'])) {
				$event->attributes = $_POST['Event'];

				$initTime = strtotime($date);

				// установка времени события в течении дня
				$event->start_time = strtotime($startTime) - strtotime('TODAY');
				$event->end_time = strtotime($endTime) - strtotime('TODAY');

				if ($initTime)
					$event->day_of_week = date('w', $initTime);
				else
					$event->day_of_week = -1;

				if ($event->validate() && !$hasErrors) {
					$currentTemplate = $event->getTemplate();
					// осталось одиночное событие или регулярное и изменяем только текущее
					if (($currentTemplate->type==EventTemplate::TYPE_SINGLE && $newType==EventTemplate::TYPE_SINGLE)) {
						// обновляем шаблон
						$currentTemplate->updateFromEvent($event, EventTemplate::TYPE_REGULAR, $initTime);
						$currentTemplate->status = EventTemplate::STATUS_ACTIVE;

						$currentTemplate->save(false);

					} elseif (($currentTemplate->type==EventTemplate::TYPE_REGULAR && $newType==EventTemplate::TYPE_REGULAR && !$changeAll)) {
						// ok
					} elseif ($currentTemplate->type==EventTemplate::TYPE_REGULAR && $newType==EventTemplate::TYPE_SINGLE) {
						// Сменили тип на одиночное событие, прибиваем младшие копии события

						// обновляем шаблон
						$currentTemplate->updateFromEvent($event, EventTemplate::TYPE_SINGLE, $initTime);
						$currentTemplate->status = EventTemplate::STATUS_ACTIVE;

						$event->removeYoungEvents();
						$currentTemplate->save(false);

					} elseif ($currentTemplate->type==EventTemplate::TYPE_SINGLE && $newType==EventTemplate::TYPE_REGULAR) {
						// Событие стало регулярным

						// обновляем шаблон
						$currentTemplate->updateFromEvent($event, EventTemplate::TYPE_REGULAR, $initTime);
						$currentTemplate->status = EventTemplate::STATUS_ACTIVE;

						// Сохраняем и создаем линки на событие
						$currentTemplate->save(false);
					} elseif ($currentTemplate->type==EventTemplate::TYPE_REGULAR && $newType==EventTemplate::TYPE_REGULAR && $changeAll) {
						// Обновляем все события
						$event->removeYoungEvents();

						// обновляем шаблон
						$currentTemplate->updateFromEvent($event, EventTemplate::TYPE_REGULAR, $initTime);
						$currentTemplate->status = EventTemplate::STATUS_ACTIVE;

						// Сохраняем и создаем линки на событие
						$currentTemplate->save(false);

					} else {
						throw new CHttpException(500, 'Invalid action');
					}

					// Установка времени самого события
					$event->start_time += $initTime;
					$event->end_time += $initTime;
					$event->save(false);

					$template->type = $currentTemplate->type;
					$this->redirect(
						Yii::app()->getUser()->getReturnUrl(array('index'))
					);
				}
			}



		}

		if (!$request->getIsPostRequest()) {
			$template = $event->getTemplate();
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
			$template->delete();
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
