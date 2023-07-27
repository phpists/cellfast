<?php

use yii\db\Migration;

class m171026_165248_category extends \noIT\category\migrations\migrate_category {
    protected $modelName = 'common\models\Category';
    protected $isTree = true;
    protected $isCover = true;
    protected $isSeo = true;

    protected function columnsCategory()
    {
        return array_merge(
            [
                'project_id' => $this->string(20)->notNull(),
            ],
            parent::columnsCategory()
        );
    }

    public function safeUp()
    {
        parent::safeUp();

        $this->createIndex('category_project', ($this->modelName)::tableName(), 'project_id');
    }

    public function safeDown()
    {
        $this->dropIndex('category_project', ($this->modelName)::tableName());

        parent::safeDown();
    }
}
