<?php

namespace noIT\core\components;

class NotEmptyUrlFilter extends CoreUrlFilter {
	public $canZero = true;
	/**
	 * Return changed $params
	 *
	 * @param $route
	 * @param $params
	 *
	 * @return array
	 */
	public function forCreate( $route, $params ) {
		if ( isset($params[$this->part]) && empty($params[$this->part]) && (!$this->canZero || $params[$this->part] !== 0) ) {
			return false;
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
		$value = $this->getValueFromRoute($route);
		if ( empty($value) && (!$this->canZero || $value !== 0) ) {
			return false;
		}
		return $this->setValueOnRoute($route, $value);
	}
}