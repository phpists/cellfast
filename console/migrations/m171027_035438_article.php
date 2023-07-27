<?php

class m171027_035438_article extends \noIT\content\migrations\migrate_content {
    protected $modelName = 'common\models\Article';

    protected function columnsContent()
    {
        return array_merge(
            [
                'project_id' => $this->string(20)->notNull(),
            ],
            parent::columnsContent()
        );
    }

    public function safeUp()
    {
        parent::safeUp();

        $this->createIndex('article_project', ($this->modelName)::tableName(), 'project_id');
    }

    public function safeDown()
    {
        $this->dropIndex('article_project', ($this->modelName)::tableName());

        parent::safeDown();
    }
}
