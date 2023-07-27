<?php

namespace noIT\cache\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\caching\Cache;
use yii\caching\DummyCache;
use yii\db\ActiveRecord;

class CacheComponent extends Cache {
	public $localCache;
	public $memoryCache;
	public $fileCache;

	public $entityLevels = [];

	/** @var Cache[] */
	private $_levels = [];

	public function init() {
		parent::init();

		if ( null !== $this->localCache && ! ($componentLocalCache = Yii::createObject($this->localCache)) ) {
			throw new Exception();
		}
		if ( !empty($componentLocalCache) ) {
			$this->_levels['local'] = $componentLocalCache;
		}

		if ( null !== $this->memoryCache && ! ($componentMemoryCache = Yii::createObject($this->memoryCache)) ) {
			throw new Exception();
		}
		if ( !empty($componentMemoryCache) ) {
			$this->_levels['memory'] = $componentMemoryCache;
		}

		if ( null !== $this->fileCache && ! ($componentFileCache = Yii::createObject($this->fileCache)) ) {
			throw new Exception();
		}
		if ( !empty($componentFileCache) ) {
			$this->_levels['file'] = $componentFileCache;
		}
	}

	protected function addValue( $key, $value, $duration ) {
		$result = true;
		foreach ( $this->_levels as $component ) {
			if ( false === $component->addValue( $key, $value, $duration ) ) {
				$result = false;
			}
		}
		return $result;
	}

	protected function getValue( $key ) {
		foreach ( $this->_levels as $component ) {
			if ( false !== $result = $component->getValue( $key ) ) {
				break;
			}
		}
		return $result;
	}

	protected function setValue( $key, $value, $duration ) {
		$result = true;
		foreach ( $this->_levels as $component ) {
			if ( false === $component->setValue( $key, $value, $duration ) ) {
				$result = false;
			}
		}
		return $result;
	}

	protected function deleteValue( $key ) {
		$result = true;
		foreach ( $this->_levels as $component ) {
			if ( false === $component->deleteValue( $key ) ) {
				$result = false;
			}
		}
		return $result;
	}

	protected function flushValues() {
		$result = true;
		foreach ( $this->_levels as $component ) {
			if ( false === $component->flushValues() ) {
				$result = false;
			}
		}
		return $result;
	}
}