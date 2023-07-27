<?php

namespace noIT\content\components;

use yii\db\ActiveQuery;
use noIT\core\components\CoreUrlFilter;

class ContentEntityUrlFilter extends CoreUrlFilter {
	public $entity = 'noIT\content\models\Content';

	public function forParse( $route, $request = null ) {
		if (!$routePart = $this->getValueFromRoute($route)) {
			return false;
		}
		if (is_string($routePart)) {
			$route = $this->setValueOnRoute($route, call_user_func([$this->entity, 'findOne'], ['slug' => $routePart]));
		}

		return parent::forParse( $route, $request );
	}

	public function forCreate( $route, $params ) {
		if (isset($params[$this->part])) {
			if ($params[$this->part] instanceof $this->entity) {
				$params[$this->part] = $params[$this->part]->slug;
			}
		}
		return parent::forCreate( $route, $params );
	}
}