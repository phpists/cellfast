<?php
namespace noIT\content\migrations;

use yii\db\Migration;
use common\helpers\AdminHelper;

class migrate_content extends Migration
{
    protected $modelName = 'noIT\content\models\Content';

    protected function columnsContent() {
        return array_merge(
            AdminHelper::migrateTablePK($this),
            [
                'slug' => $this->string(255),
                'image' => $this->string(255),
                'image_list' => $this->string(255),
            ],
            AdminHelper::migrateTableLangs('name', $this->string('150')),
            AdminHelper::migrateTableLangs('teaser', $this->text()),
            AdminHelper::migrateTableLangs('body', $this->text()),
            AdminHelper::migrateTableLangs('seo_h1', $this->string('150')),
            AdminHelper::migrateTableLangs('seo_title', $this->string('150')),
            AdminHelper::migrateTableLangs('seo_description', $this->text()),
            AdminHelper::migrateTableLangs('seo_keywords', $this->text()),
            AdminHelper::migrateTableStatus($this, ($this->modelName)::STATUS_ACTIVE),
            AdminHelper::migrateTableTS($this),
            AdminHelper::migrateTablePublished($this)
        );
    }

    public function safeUp()
    {
        $this->createTable(($this->modelName)::tableName(), $this->columnsContent(), AdminHelper::migrateTableOptions($this->db->driverName));
    }

    public function safeDown()
    {
        $this->dropTable(($this->modelName)::tableName());
    }
}
