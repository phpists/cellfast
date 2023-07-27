<?php

namespace noIT\content\models;

use Yii;
use yii\base\Model;

class BaseContentEntity extends Model {
	protected $_translatedValues = [];

	protected $_identity;

	public static $activerecordCass;

	public $id;

	public function __get( $name ) {
		foreach ( Yii::$app->languages->languages as $language ) {
			if ( false !== ( $_name = $this->parseNameByLanguage($name, $language->suffix) ) && in_array($_name, $this->_translatedAttributes) ) {
				return @$this->_translatedValues[$_name][$language->suffix];
				break;
			}
		}

		return parent::__get( $name );
	}

	public function __set( $name, $value ) {
		foreach ( Yii::$app->languages->languages as $language ) {
			if ( false !== ( $_name = $this->parseNameByLanguage($name, $language->suffix) ) && in_array($_name, $this->_translatedAttributes) ) {
				$this->_translatedValues[$_name][$language->suffix] = $value;
				return;
				break;
			}
		}

		parent::__set( $name, $value );
	}

	public function getIdentity() {
		if ( ! $this->id ) {
			return null;
		}
		if ( null === $this->_identity ) {
			$ar = static::$activerecordCass;
			$this->_identity = $ar::findOne($this->id);
		}
		return $this->_identity;
	}

	protected function parseNameByLanguage( $name, $langSuffix ) {
		if ( false !== ( $i = strpos( $name, $langSuffix ) ) && ( $i + strlen($langSuffix) ) === strlen($name) ) {
			return substr($name, 0, $i - 1);
		}
		return false;
	}
}
