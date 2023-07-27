<?php
namespace noIT\junction\helpers;

class JunctionHelper {
    public static function getTableName($sourceTableName, $entityTableName, $for_prefix = false, $delimiter = null) {
        if (null === $delimiter) {
            $delimiter = "_has_";
        }
        $tableName = "{$entityTableName}{$delimiter}{$sourceTableName}";
        return $for_prefix ? "{{%$tableName}}" : $tableName;
    }

    public static function getFieldName($tableName, $key = 'id') {
        return strtolower("{$tableName}_{$key}");
    }

    public static function getTableNameByModel($model, $clean = true) {
        $tableName = ($model)::tableName();
        return $clean ? \Yii::$app->db->quoteTableName($tableName) : $tableName;
    }
}