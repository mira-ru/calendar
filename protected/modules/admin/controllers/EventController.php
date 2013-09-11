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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
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
//		$changeAll = (bool)$request->getParam('change_all', true);
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

				if ($template->type == EventTemplate::TYPE_SINGLE) {
					$template->status = EventTemplate::STATUS_DISABLED; // отключаем повторение для одиночных событий
				} else {
					$template->status = EventTemplate::STATUS_ACTIVE;
				}

			}

			if ($template->validate()) { // Создание событий
				$template->save(false);
				$this->redirect(array('index'));
			}
		}

		if (!$request->getIsPostRequest()) {
			$date = date('d.m.Y');
			$startTime = '7.00';
			$endTime = '8.00';
		}

		$centers = Center::model()->findAllByAttributes(array('status'=>Center::STATUS_ACTIVE));
		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE));
		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));

		$this->render('create',array(
			'template' => $template,
			'centers' => $centers,
			'services' => $services,
			'halls' => $halls,
			'date' => $date,
//			'changeAll' => $changeAll,
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

		$changeAll = (bool)$request->getParam('change_all', true);
		$startTime = $request->getParam('start_time');
		$endTime = $request->getParam('end_time');
		$date = $request->getParam('date');
//		$modifyAll = (bool)$request->getParam('modify_all', true); // флаг для смены типа, затрагивает всех или нет


		if($request->getIsPostRequest()) {

			$newType = isset($_POST['EventTemplate']['type']) ? EventTemplate::TYPE_SINGLE : intval($_POST['EventTemplate']['type']);
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
					if (
						($currentTemplate->type==EventTemplate::TYPE_SINGLE && $newType==EventTemplate::TYPE_SINGLE)
						|| ($currentTemplate->type==EventTemplate::TYPE_REGULAR && $newType==EventTemplate::TYPE_REGULAR && !$changeAll)
					) {
						// Установка времени самого события
						$event->start_time += $initTime;
						$event->end_time += $initTime;

						$event->save(false);
					} elseif ($currentTemplate->type==EventTemplate::TYPE_REGULAR && $newType==EventTemplate::TYPE_SINGLE) {
						// Сменили тип на одиночное событие, прибиваем младшие копии события
						$currentTemplate->type = EventTemplate::TYPE_SINGLE;

						// Установка времени самого события
						$event->start_time += $initTime;
						$event->end_time += $initTime;

						$event->save(false);
						$event->removeYoungEvents();

					} elseif ($currentTemplate->type=EventTemplate::TYPE_SINGLE && $newType==EventTemplate::TYPE_REGULAR) {
						// Событие стало регулярным

						// обновляем шаблон
						$currentTemplate->updateFromEvent($event, EventTemplate::TYPE_REGULAR, $initTime);

						// Установка времени самого события
						$event->start_time += $initTime;
						$event->end_time += $initTime;

						// Сохраняем и создаем линки на событие
						$currentTemplate->save(false);
					} elseif ($currentTemplate->type==EventTemplate::TYPE_REGULAR && $newType==EventTemplate::TYPE_REGULAR && $changeAll) {
						// Обновляем все события
						$event->removeYoungEvents();

						// обновляем шаблон
						$currentTemplate->updateFromEvent($event, EventTemplate::TYPE_REGULAR, $initTime);

						// Установка времени самого события
						$event->start_time += $initTime;
						$event->end_time += $initTime;

						// Сохраняем и создаем линки на событие
						$currentTemplate->save(false);

					} else {
						throw new CHttpException(500, 'Invalid action');
					}
				}
			}


			FirePHP::getInstance()->fb($event->getErrors());
			FirePHP::getInstance()->fb($event->attributes);

//			$this->redirect(array('index'));


//			$model->attributes=$_POST['User'];
//			if($model->save())
//				$this->redirect(array('index','id'=>$model->id));
		}

		if (!$request->getIsPostRequest()) {
			$template = $event->getTemplate();
			$date = date('d.m.Y', $event->start_time);
			$startTime = date('H.i', $event->start_time);
			$endTime = date('H.i', $event->end_time);
		}

		$centers = Center::model()->findAllByAttributes(array('status'=>Center::STATUS_ACTIVE));
		$services = Service::model()->findAllByAttributes(array('status'=>Service::STATUS_ACTIVE));
		$halls = Hall::model()->findAllByAttributes(array('status'=>Hall::STATUS_ACTIVE));

		$this->render('update',array(
			'event'=>$event,
			'template'=>$template,

			'centers' => $centers,
			'services' => $services,
			'halls' => $halls,

			'date' => $date,
			'changeAll' => $changeAll,
			'startTime' => $startTime,
			'endTime' => $endTime,

//			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->status = User::STATUS_DELETED;
		$model->save(false);

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Event('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
