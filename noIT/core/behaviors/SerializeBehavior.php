<?php

namespace noIT\core\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class SerializeBehavior extends Behavior
{
	public $attribute;
	public $data;

	public function events()
	{
		return [
			ActiveRecord::EVENT_BEFORE_UPDATE=> 'setRelations',
			ActiveRecord::EVENT_BEFORE_INSERT=> 'setRelations',
			ActiveRecord::EVENT_AFTER_FIND => 'getRelations',
		];
	}

	public function setRelations()
	{
		$this->owner->{$this->attribute} = serialize($this->data);
	}

	public function getRelations()
	{
		$this->owner->{$this->attribute} = unserialize($this->owner->{$this->attribute});
	}
}