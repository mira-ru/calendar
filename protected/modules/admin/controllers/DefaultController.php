<?php

class DefaultController extends AdminController
{
	/**
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'roles'=>array(
					Admin::ROLE_POWERADMIN,
					Admin::ROLE_ADMIN,
					Admin::ROLE_READER,
					Admin::ROLE_DRAFTSAVER,
					Admin::ROLE_PUBLISHER,
				),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$this->render('index');
	}
}