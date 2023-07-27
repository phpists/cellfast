<?php

namespace cellfast\components;

use common\models\Product;
use noIT\core\components\CoreUrlFilter;

class ProductUrlFilter extends CoreUrlFilter
{
	public $entity = 'common\models\Product';
	public $partItem = 'item';

	public function forParse( $route, $request = null )
	{
		if (!$product = $this->getValueFromRoute($route)) {
			return false;
		}
		if (is_string($product)) {
			/** @var Product $product */
			$route = $this->setValueOnRoute($route, call_user_func([$this->entity, 'findBySlug'], $product));
		}

		return parent::forParse( $route, $request );
	}

	public function forCreate( $route, $params )
	{
		if (isset($params[$this->part])) {
			if ($params[$this->part] instanceof $this->entity) {
				$params[$this->part] = $params[$this->part]->slug;
			}
		}
		return parent::forCreate( $route, $params );
	}
}