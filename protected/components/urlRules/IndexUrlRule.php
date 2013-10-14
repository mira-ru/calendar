<?php
class IndexUrlRule extends CBaseUrlRule
{
	public function createUrl($manager, $route, $params, $ampersand)
	{
		if ($route == 'site/index') {
			$time = empty($params['time']) ? DateMap::currentDay(time()) : $params['time'];
			$class = isset($params['class_id']) ? Config::$routeMap[$params['class_id']] : '';
			$id = isset($params['id']) ? $params['id'] : '';

			$url = 'c/'.$time;
			if ( !empty($class) && !empty($id) ) {
				$url .= '/'.$class.'/'.$id;
			}
			if (isset($params['popup'])) {
				$url .= '?'.$params['popup'];
			}

			return $url;
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
		if ($request->getIsAjaxRequest())
			return false;

		if (empty($pathInfo)) {
			return 'site/index';
		}

		/**
		 * 1 - time
		 * 2 - model class
		 * 3 - model id
		 */
		if (preg_match('@^c/([\d]+)/([\w]+)/([\d]+)?$@u', $pathInfo, $pathArray))
		{
			$class_id = array_search($pathArray[2], Config::$routeMap);
			if ($class_id === false) { throw new CHttpException(404); }

			$_GET['time'] = $pathArray[1];
			$_GET['class_id'] = $class_id;
			$_GET['model_id'] = $pathArray[3];

			return 'site/index';
		}
		// для дефолтных страниц, с указанием времени
		if (preg_match('@^c/([\d]+)?$@u', $pathInfo, $pathArray))
		{
			$_GET['time'] = $pathArray[1];
			return 'site/index';
		}

		return false;
	}
}