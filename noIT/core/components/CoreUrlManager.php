<?php

namespace noIT\core\components;

use codemix\localeurls\UrlManager;
use Yii;

/**
 * Class CoreUrlManager
 * @package noIT\core\components
 *
 */
class CoreUrlManager extends UrlManager {
	public function getCurrentRoute() {
		list($route, $params) = $this->parseRequest(Yii::$app->request);
		if (!$params) {
			$params = [];
		}
		$route = array_merge([$route], $params);
		return $route;
	}
}
