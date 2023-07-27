<?php
namespace noIT\feature\migrations;

use yii\db\Migration;
use common\helpers\AdminHelper;

class migrate_feature extends Migration
{
    protected $modelName = 'noIT\feature\models\SingleFeature';

    protected function columns() {
        return array_merge(
            AdminHelper::migrateTablePK($this),
            [
                'id' => $this->primaryKey(),
                'slug' => $this->string(255),
            ],
            AdminHelper::migrateTableLangs('name', $this->string('150')),
            AdminHelper::migrateTableLangs('caption', $this->text()),
            /** @todo SeoBehavior */
            AdminHelper::migrateTableStatus($this, ($this->modelName)::STATUS_ACTIVE),
            AdminHelper::migrateTableTS($this)
        );
    }

    public function safeUp()
    {
        $this->createTable(($this->modelName)::tableName(), $this->columns(), AdminHelper::migrateTableOptions($this->db->driverName));
    }

    public function safeDown()
    {
        $this->dropTable(($this->modelName)::tableName());
    }
}
