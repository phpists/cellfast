<?php

namespace noIT\content\components;

use Yii;
use noIT\core\components\CoreUrlFilter;

class CheckPaginatorUrlFilter extends CoreUrlFilter {
	public function forParse( $route, $request = null ) {
		if ($request && isset($request->queryParams[$this->part])) {
			if ($request->queryParams[$this->part] === '1' || strtolower($request->queryParams[$this->part]) === 'all') {
				$get = $request->queryParams;
				unset( $get[$this->part] );
				Yii::$app->getResponse()->redirect( array_merge( [ $request->getPathInfo() ], $get ), 301 )->send();
				Yii::$app->end();
			}
		}

		return $route;
	}
}