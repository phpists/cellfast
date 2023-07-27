<?php
namespace noIT\junction\migrations;

use yii\db\Migration;
use yii\console\Exception;
use common\helpers\AdminHelper;

/** TODO - Именования индексов могут быть слишком длинные. Пришлось кастомно назвать таблицу product_item_has_pr_feature_value */

class migrate_junction extends Migration
{
    protected $sourceModelName;
    protected $entityModelName;
    protected $junctionTableName;
    protected $junctionSourceField;
    protected $junctionEntityField;
    protected $junctionDelimiterName = "_has_";
    protected $timestamps = true;
//    protected $junctionAddPK = []; @todo Maybe

    public function init()
    {
        parent::init();

        if (count(($this->sourceModelName)::primaryKey()) > 1 || count(($this->entityModelName)::primaryKey()) > 1) {
            throw new Exception('Multiple keys are not sapported');
        }
    }

    protected function columnsJunction() {
        $columns = [];
        foreach (['sourceModelName', 'entityModelName'] as $model) {
            foreach (($this->$model)::primaryKey() as $item) {
                $columns[self::getFieldName(self::getTableNameByModel($this->$model), strtolower($item))] = $this->integer();
            }
        }
        if ($this->timestamps) {
            $columns = array_merge($columns, AdminHelper::migrateTableTS($this));
        }
        return $columns;
    }

    protected function getJunctionSourceField() {
        if (null === $this->junctionSourceField) {
            $this->junctionSourceField = self::getFieldName(($this->sourceModelName)::tableName());
        }
        return $this->junctionSourceField;
    }

    protected function getJunctionEntityField() {
        if (null === $this->junctionEntityField) {
            $this->junctionEntityField = self::getFieldName(($this->entityModelName)::tableName());
        }
        return $this->junctionEntityField;
    }
    
    protected function getJunctionTableName($quoted = false) {
        if (null === $this->junctionTableName) {
            $this->junctionTableName = str_replace(["{{%", "}}"], ['', ''], self::getTableName(($this->sourceModelName)::tableName(), ($this->entityModelName)::tableName(), true, $this->junctionDelimiterName));
        }
        return $quoted ? $this->db->quoteTableName($this->junctionTableName) : $this->junctionTableName;
    }


    public function safeUp()
    {
        $this->createTable($this->getJunctionTableName(true), $this->columnsJunction(), AdminHelper::migrateTableOptions($this->db->driverName));

        $this->createIndex($this->getJunctionTableName() .'_index', $this->getJunctionTableName(), [$this->getJunctionSourceField(), $this->getJunctionEntityField()], true);

        $this->addForeignKey($this->getJunctionTableName() .'_'. $this->getJunctionSourceField() .'_fk', $this->getJunctionTableName(), $this->getJunctionSourceField(), ($this->sourceModelName)::tableName(), ($this->sourceModelName)::primaryKey(), 'CASCADE', 'CASCADE');
        $this->addForeignKey($this->getJunctionTableName() .'_'. $this->getJunctionEntityField() .'_fk', $this->getJunctionTableName(), $this->getJunctionEntityField(), ($this->entityModelName)::tableName(), ($this->entityModelName)::primaryKey(), 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable($this->getJunctionTableName());
    }

    public static function getTableName($sourceTableName, $entityTableName, $for_prefix = false, $delimiter = null) {
        if (null === $delimiter) {
            $delimiter = "_has_";
        }
        $tableName = str_replace(["{{%", "}}"], ['', ''], "{$entityTableName}{$delimiter}{$sourceTableName}");
        return $for_prefix ? "{{%$tableName}}" : $tableName;
    }

    public static function getFieldName($tableName, $key = 'id') {
        $tableName = str_replace(["{{%", "}}"], ['', ''], $tableName);
        return strtolower("{$tableName}_{$key}");
    }

    public static function getTableNameByModel($model, $clean = true) {
        $tableName = ($model)::tableName();
        return $clean ? str_replace(["{{%", "}}"], ['', ''], $tableName) : $tableName;
    }
}
