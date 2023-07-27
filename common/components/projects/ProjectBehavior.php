<?php
namespace common\components\projects;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class ProjectBehavior extends Behavior
{
    public $attribute_id = 'project_id';
    public $attribute_object = 'project';

    public function attach($owner)
    {
        parent::attach($owner);

        $this->owner->setAttribute($this->attribute_object, $owner->{$this->attribute_id});
    }
}