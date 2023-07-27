<?php

namespace noIT\cache\components;

use yii\caching\Cache;

class LocalCache extends Cache {
	static $_localCache = [];

	public function set( $key, $value, $duration = null, $dependency = null ) {
		return $this->setValue($key, $value, $duration);
	}

	public function get( $key ) {
		return $this->getValue( $key );
	}

	protected function flushValues() {
		static::$_localCache = [];
		return true;
	}

	protected function setValue( $key, $value, $duration ) {
		static::$_localCache[$key] = $value;
		return true;
	}

	protected function getValue( $key ) {
		return isset(static::$_localCache[$key]) ? static::$_localCache[$key] : false;
	}

	protected function addValue( $key, $value, $duration ) {
		$this->setValue($key, $value, $duration);
		return true;
	}

	protected function deleteValue( $key ) {
		unset(static::$_localCache[$key]);
		return true;
	}
}