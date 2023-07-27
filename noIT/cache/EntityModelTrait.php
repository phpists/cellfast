<?php

namespace noIT\cache;

use yii\db\ActiveRecord;

// TODO - Переделать в behavior
trait EntityModelTrait {
	/**
	 * @var $_entityModelObject ActiveRecord
	 */
	protected $_entityModelObject;

	public function getModelEntity() {
		if ( null === $this->entityModel ) {
			return null;
		}

		if ( null === $this->_entityModelObject ) {
			$data = method_exists($this, 'prepareEntity') ? $this->prepareEntity() : $this->toArray();
			$this->_entityModelObject = new $this->entityModel( $data );
		}

		return $this->_entityModelObject;
	}
}
