<?php
class IndexUrlRule extends CBaseUrlRule
{
	public function createUrl($manager, $route, $params, $ampersand)
	{
		if ($route == 'site/index') {
			$centerId = empty($params['center_id']) ? 0 : $params['center_id'];
			$serviceId = empty($params['service_id']) ? 0 : $params['service_id'];
			$directionId = empty($params['direction_id']) ? 0 : $params['direction_id'];
			$time = empty($params['time']) ? DateMap::currentDay(time()) : $params['time'];

			return 'c/'.$centerId.'/'.$serviceId.'/'.$directionId.'/'.$time;
		}
		return false;
	}

	/**
	 * @param $manager
	 * @param $request CHttpRequest
	 * @param $pathInfo
	 * @param $rawPathInfo
	 * @return bool|mixed|string
	 * @throws CHttpException
	 */
	public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
	{
		if (empty($pathInfo)) {
			$request->redirect(
				$this->createUrl($manager, 'site/index', array(), '&'),
				true,
				302
			);
		}
		/*
		 * 1 - center_id
		 * 2 - service_id
		 * 3 - direction_id
		 * 4 - time
		 */
		if (preg_match('@^c/([\d]+)/([\d]+)/([\d]+)/([\d]+)?(?:/([-\d]+))?$@u', $pathInfo, $pathArray))
		{
			$params = array(
				'center_id' => $pathArray[1],
				'service_id' => $pathArray[2],
				'direction_id' => $pathArray[3],
				'time' => $pathArray[4],
			);
			$_GET += $params;

			return 'site/index';
		}

		return false;
	}
}