<?php
namespace common\components;

use Yii;

class CategoryComponent extends \noIT\category\components\CategoryComponent
{
    public $categoryModel = 'common\models\Category';

    public $project;

    public function init()
    {
        parent::init();

        /** Auto create project fron $app->id */
        if (!$this->project && Yii::$app->id !== 'cellfast-admin') {
            $this->project = Yii::$app->id;
        } else {
            $this->visible_only = false;
        }
    }
}