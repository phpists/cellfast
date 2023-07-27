<?php

namespace noIT\core\components;

use Yii;
use yii\web\UrlRule;

/**
 * Class CoreUrlManager
 *
 * @package noIT\core\components
 */
class CoreUrlRule extends UrlRule {
	public $filters = [];

	public function init() {
		parent::init();
	}

	public function parseRequest( $manager, $request ) {
		if ( false === $route = parent::parseRequest( $manager, $request ) ) {
			return $route;
		}

		if ( null !== $this->filters && is_array($this->filters) ) {
			foreach ($this->filters as $key => $filter) {
				$filter['part'] = isset($filter['part']) ? $filter['part'] : $key;
				/** @var CoreUrlFilter $filter */
				$filter = Yii::createObject( $filter );
				$route = $filter->forParse( $route, $request );
			}
		}

		return $route;
	}

	public function createUrl( $manager, $route, $params ) {
		if ( !empty($this->filters) && is_array($this->filters) ) {
			foreach ($this->filters as $key => $filter) {
				$filter['part'] = isset($filter['part']) ? $filter['part'] : $key;
				/** @var CoreUrlFilter $filter */
				$filter = Yii::createObject( $filter );
				$params = $filter->forCreate( $route, $params );
			}
		}
		return parent::createUrl($manager, $route, $params);
	}
	
	protected function getPath($request) {
		static $path;
		if (null === $path) {
			$path = $request->getPathInfo();
		}
		return $path;
	}
}