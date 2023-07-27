<?php

namespace noIT\feature\components;

use noIT\core\components\CoreUrlFilter;

class FeatureSingleUrlFilter extends CoreUrlFilter {
	/**
	 * Return changed $params
	 *
	 * @param $route
	 * @param $params
	 *
	 * @return array
	 */
	public function forCreate( $route, $params ) {
		$params[$this->part] = @$params[$this->part];
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
		$value = $this->getValueFromRoute($route);
		return $this->setValueOnRoute($route, $value);
	}
}