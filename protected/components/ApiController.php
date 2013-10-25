<?php

abstract class ApiController extends CController
{
	abstract public function actionList();
	abstract public function actionView($id);
	abstract public function actionCreate();
	abstract public function actionUpdate($id);
	abstract public function actionDelete($id);

}
