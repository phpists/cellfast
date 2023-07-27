<?php

namespace noIT\feature\components;

use Yii;
use noIT\core\components\CoreUrlFilter;

class FeatureMultipleUrlFilter extends CoreUrlFilter {
	public $componentId = 'feature';

	/**
	 * Return changed $params
	 *
	 * @param $route
	 * @param $params
	 *
	 * @return array
	 */
	public function forCreate( $route, $params ) {
		if (!empty($params[$this->part])) {
			// Yii::$app->get have self exception
			$params[$this->part] = Yii::$app->get($this->componentId)->createFilter($params[$this->part]);
		}
		return $params;
	}

	/**
	 * Return changed $route
	 *
	 * @param $route
	 * @param null $request
	 *
	 * @return array
	 */
	public function forParse( $route, $request = null ) {
		$value = Yii::$app->get($this->componentId)->parseFilter($this->getValueFromRoute($route));
		return $this->setValueOnRoute($route, $value);
	}
}