<?php

namespace noIT\core\components;

abstract class CoreUrlFilter {
	public $part;

	public function forCreate( $route, $params ) {
		return $params;
	}

	public function forParse( $route, $request = null ) {
		return $route;
	}

	protected function getValueFromRoute( $route ) {
		return isset($route[1][$this->part]) ? $route[1][$this->part] : null;
	}

	protected function setValueOnRoute( $route, $value ) {
		$route[1][$this->part] = $value;
		return $route;
	}
}