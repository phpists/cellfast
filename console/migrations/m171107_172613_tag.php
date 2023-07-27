<?php

class m171107_172613_tag extends \noIT\feature\migrations\migrate_feature {
    protected $modelName = 'common\models\Tag';

    protected function columns()
    {
        return array_merge(
            [
                'project_id' => $this->string(20)->notNull(),
            ],
            parent::columns()
        );
    }

    public function safeUp()
    {
        parent::safeUp();

        $this->createIndex('tag_project', ($this->modelName)::tableName(), 'project_id');
    }

    public function safeDown()
    {
        $this->dropIndex('tag_project', ($this->modelName)::tableName());

        parent::safeDown();
    }
}
