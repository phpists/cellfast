<?php

namespace noIT\category\migrations;

use yii\db\Migration;
use yii\console\Exception;
use common\helpers\AdminHelper;
use noIT\category\models\Category;

class migrate_category extends Migration
{
    protected $modelName = 'noIT\category\models\Category';
    protected $isTree = false;
    protected $isCover = true;
    protected $isSeo = false;
    protected $isSorted = true;

    public function init()
    {
        parent::init();

        if (self::className() === 'migrate_category') {
            throw new Exception("You can not apply migration directly");
        }
    }

    protected function columnsCategory() {
        return array_merge(
            AdminHelper::migrateTablePK($this),
            ($this->isTree ? [
                'parent_id' => $this->integer()->notNull()->defaultValue(0),
                'path' => $this->string(255)->notNull(),
                'depth' => $this->integer(2)->notNull(),
            ] : []),
            [
                'native_name' => $this->string(100)->notNull(),
                'slug' => $this->string(100)->notNull(),
            ],
            AdminHelper::migrateTableLangs('name', $this->string('150')),
            AdminHelper::migrateTableLangs('description', $this->text()),
            ($this->isSeo ?
                array_merge(
                    AdminHelper::migrateTableLangs('seo_h1', $this->string('150')),
                    AdminHelper::migrateTableLangs('seo_title', $this->string('150')),
                    AdminHelper::migrateTableLangs('seo_description', $this->text()),
                    AdminHelper::migrateTableLangs('seo_keywords', $this->text())
                ) : []),
            ($this->isCover ? [
                'image' => $this->string(255),
            ] : []),
            ($this->isSorted ? [
                AdminHelper::FIELDNAME_SORT => $this->integer()->defaultValue(0),
            ] : []),
            AdminHelper::migrateTableStatus($this, ($this->modelName)::STATUS_ACTIVE),
            AdminHelper::migrateTableTS($this)
        );
    }

    public function safeUp()
    {
        $this->createTable(($this->modelName)::tableName(), $this->columnsCategory(), AdminHelper::migrateTableOptions($this->db->driverName));
    }

    public function safeDown()
    {
        $this->dropTable(($this->modelName)::tableName());
    }
}
