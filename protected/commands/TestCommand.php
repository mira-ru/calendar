<?php
/**
 * Консольные команды для периодического выполнения
 *
 * @author alexsh
 */
class TestCommand extends CConsoleCommand
{
	public function actionTest()
	{
		$token = '';

		Yii::import('ext.googleApi.Google_Client');
		Yii::import('ext.googleApi.contrib.Google_CalendarService');
		$CLIENT_ID = '508082782199.apps.googleusercontent.com';
		$clientSecret = '3aINSX9TfUmldjHwmnr9tRH7';
		$SERVICE_ACCOUNT_NAME = '508082782199@developer.gserviceaccount.com';

		// Make sure you keep your key.p12 file in a secure location, and isn't
		// readable by others.
		$KEY_FILE = Yii::app()->basePath . '/data/googleApi/key.p12';

		// Load the key in PKCS 12 format (you need to download this from the
		// Google API Console when the service account was created.
		$client = new Google_Client();
		$client->setClientId($CLIENT_ID);


		$client->setApplicationName("Google Prediction Sample");

		if (!empty($token)) {
			$client->setAccessToken($token);
		}

//		$prn = 'b5tsk41pl49a9mpjfm6c8hqgc8@group.calendar.google.com'; /* replace with your real calendar name */;

		// Load the key in PKCS 12 format (you need to download this from the
		// Google API Console when the service account was created.
		$key = file_get_contents($KEY_FILE);
		$client->setAssertionCredentials(new Google_AssertionCredentials(
				$SERVICE_ACCOUNT_NAME,
				array('https://www.googleapis.com/auth/calendar'),
				$key
//				,
//				'notasecret',
//				'http://oauth.net/grant_type/jwt/1.0/bearer',
//				$prn
			)
		);

		$client->setClientId($CLIENT_ID);
		$client->setClientSecret($clientSecret);
		$service = new Google_CalendarService($client);

		$calList = $service->calendarList->listCalendarList(array('maxResults'=>20, 'showHidden'=>true, 'minAccessRole'=>'writer'));
		print_r($calList);
		echo "\n\n";

//		$calendar = $service->calendarList->get('b5tsk41pl49a9mpjfm6c8hqgc8@group.calendar.google.com');
//		print_r($calendar);


		// We're not done yet. Remember to update the cached access token.
		// Remember to replace $_SESSION with a real database or memcached.
		if ($client->getAccessToken()) {
			$token = $client->getAccessToken();
		}


		var_dump($token);

		echo "\n\n";

		print_r('test ok');
		echo "\n\n";
		die();
	}
}