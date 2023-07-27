<?php

namespace noIT\soap\models;

use common\models\soap\E1cSession;
use noIT\soap\SoapServerModule as SOAP;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Transaction;

abstract class SoapE1cModel extends ActiveRecord
{
    const INDEX = "index";
    const FK_STRICT = "strict";
    const FK_SOFT = "soft";
    const FK_STRICT_NULL = "null";
    const TYPE_GUID = "convertToGuid";
    const TYPE_STRING = "convertToString";
    const TYPE_ENUM = "convertToEnum";
    const TYPE_NUMBER = "convertToNumber";
    const TYPE_BLOB = "convertToBlob";
    const TYPE_BOOLEAN = "convertToBoolean";
    const TYPE_DATE = "convertToDate";
    const TYPE_TIMESTAMP = "convertToTimestamp";

    protected static $schema;
    protected static $tableLayout = [];
    protected static $searchMatrices;

    /**
     * @inheritdoc
     */
    protected static function getRowIndex($row , $columnsPk)
    {
        $result = '';
        foreach ($columnsPk as $column => $type) {
            $result .= ((empty($result)) ? '' : '^') . $row[$column];
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public static function quoteName($name)
    {
        return strpos($name, '`') !== false ? $name : "`$name`";
    }

    /**
     * @inheritdoc
     */
    protected static function setSearchMatrices($needToTruncateTable)
    {
        $result = [];
        foreach (self::$tableLayout['foreignKeys'] as $fieldName => $fkConfig) {
            $result[$fieldName] = [];
            if ($needToTruncateTable && $fkConfig['class'] === get_called_class()) {
                continue;
            }
            $columns = [
                $fkConfig['refField'],
                ($fkConfig['searchField'] === 'guid')
                    ? new Expression("UUIDFromBin(`guid`) AS `guid`")
                    : $fkConfig['searchField']
            ];
            $query = $fkConfig['class']::find()->select($columns)->asArray();
            foreach ($query->each() as $row) {
                $result[$fieldName][$row[$fkConfig['searchField']]] = $row[$fkConfig['refField']];
            }
        }
        $result['primaryKey'] = [];
        if (self::$tableLayout['updatable'] && !$needToTruncateTable) {
            $columns = [];
            foreach (self::$tableLayout['primaryKey'] as $column => $type) {
                $columns[] = ($type === self::TYPE_GUID)
                    ? new Expression("UUIDFromBin(`{$column}`) AS `{$column}`")
                    : $column;
            }
            $query = static::find()->select($columns)->asArray();
            $columnsPk = self::$tableLayout['primaryKey'];
            foreach ($query->each() as $row) {
                $result['primaryKey'][self::getRowIndex($row, $columnsPk)] = true;
            }
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    public static function loadAndSave($needToTruncateTable, $rows)
    {
        $startTimePoint = date_create();
        $db = self::getDb();
        if (!isset(self::$schema)) {
            self::$schema = $db->getSchema();
        }
        $tableNameRaw = self::$schema->getRawTableName(static::tableName());
        if (!E1cSession::checkQueue($tableNameRaw)) {
            throw new \ErrorException("Table `{$tableNameRaw}` out of turn");
        }
        $tableName = self::quoteName($tableNameRaw);
        self::$tableLayout = static::getTableLayout();
        if (!self::$tableLayout['updatable'] && !$needToTruncateTable) {
            throw new \ErrorException("Table {$tableName} must be fully refilled");
        }
        self::$searchMatrices = self::setSearchMatrices($needToTruncateTable);
        $transaction = $db->beginTransaction(Transaction::SERIALIZABLE);
        try {
            if ($needToTruncateTable) {
                if (SOAP::getInstance()->params['debug']) {
                    $db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
                    $db->createCommand()->truncateTable($tableName)->execute();
                    $db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();
                } else {
                    $db->createCommand()->truncateTable($tableName)->execute();
                }
            }
            $maxSqlBufferSize = SOAP::getInstance()->params['maxSqlBufferSize'];
            $buffer = [];
            $extraRows = [];
            $rowKeys = '';
            foreach ($rows as $row) {
                if (empty($rowKeys)) {
                    $rowKeys = array_flip(array_keys($row));
                }
                $sqlQuery = self::prepareSql($row, $rowKeys, $tableName);
                if (is_array($sqlQuery)) {
                    $extraRows[] = $sqlQuery['extraRow'];
                    $sqlQuery = $sqlQuery['sqlQuery'];
                }
                if ($maxSqlBufferSize > 0) {
                    $buffer[] = $sqlQuery;
                    if (count($buffer) > $maxSqlBufferSize) {
                        $db->createCommand(implode("; ", $buffer))->execute();
                        $buffer = [];
                    }
                } else {
                    $db->createCommand($sqlQuery)->execute();
                }
            }
            if (!empty($buffer)) {
                $db->createCommand(implode("; ", $buffer))->execute();
            }
        } catch (\ErrorException $e) {
            $transaction->rollBack();
            throw $e;
        }
        $transaction->commit();
        if (!empty($extraRows)) {
            self::$tableLayout['hierarchical']['class']::loadAndSave(false, $extraRows);
        }
        $date_range = date_diff(date_create(), $startTimePoint);
        E1cSession::shiftQueue($tableNameRaw, $date_range->s);
    }

    /**
     * @inheritdoc
     */
    public static function prepareSql($row, $rowKeys, $tableName)
    {
        $result = [];
        $isExtraRow = false;
        $loadableAttributes = self::$tableLayout['loadableAttributes'];
        $foreignKeys = self::$tableLayout['foreignKeys'];
        $columnsPk = self::$tableLayout['primaryKey'];
        foreach ($loadableAttributes as $attribute => $type) {
            if (isset($row[$attribute])) {
                $result[$attribute] = self::$type($attribute, $row[$attribute]);
                if (isset($foreignKeys[$attribute])) {
                    $foreignKeyConfig = $foreignKeys[$attribute];
                    $result_id = self::convertToId($attribute, $row[$attribute], $foreignKeyConfig['type']);
                    $result[$foreignKeyConfig['targetField']] = $result_id['value'];
                    if ($result_id['attr2Null']) {
                        $result[$attribute] = $result_id['value'];
                        if ($result_id['extraRow']) {
                            $isExtraRow = true;
                        }
                    }
                }
                unset($rowKeys[$attribute]);
            } else {
                self::generateRequiredError($attribute, '');
            }
        }
        if (!empty($rowKeys)) {
            throw new \ErrorException(
                "Failed to set unsafe attributes " .
                implode(", ", array_keys($rowKeys)) . " in '" . get_called_class() . "'");
        }
        if (empty($result)) {
            throw new \ErrorException(
                "Failed to execute empty SQL-query in '" . get_called_class() . "'");
        }
        $isNewRow = true;
        if (self::$tableLayout['updatable']) {
            if (!empty(self::$searchMatrices['primaryKey']) &&
                isset(self::$searchMatrices['primaryKey'][self::getRowIndex($row, $columnsPk)])) {
                $isNewRow = false;
            }
        }
        if ($isNewRow) {
            $rawSql = "INSERT INTO {$tableName} (" .
                implode(', ', array_keys($result)) . ")" .
                " VALUES (" . implode(', ', $result) . ")";
        }
        else {
            $setConditions = '';
            $whereConditions = '';
            foreach ($result as $column => $value) {
                if (isset($columnsPk[$column])) {
                    $whereConditions.= ((empty($whereConditions)) ? '' : ' AND ') . $column . '=' . $value;
                } else {
                    $setConditions.= ((empty($setConditions)) ? '' : ', ') . $column . '=' . $value;
                }
            }
            $rawSql = "UPDATE {$tableName} SET " . $setConditions . ' WHERE ' . $whereConditions;
        }
        if ($isExtraRow) {
            $hierarchicalField = self::$tableLayout['hierarchical']['field'];
            $response['sqlQuery'] = $rawSql;
            $extraRow = [];
            foreach ($columnsPk as $column => $type) {
                $extraRow[$column] = $row[$column];
            }
            $extraRow[$hierarchicalField] = $row[$hierarchicalField];
            $response['extraRow'] = $extraRow;
            return $response;
        } else {
            return $rawSql;
        }
    }

    /**
     * @inheritdoc
     */
    protected static function generateRequiredError($name, $value)
    {
        throw new \ErrorException(
            "Attribute '{$name}' ({$value}) in '" . get_called_class() . "' can't be null");
    }

    public static function convertToBoolean($attribute, $value, $directSql = true)
    {
        if ($value === "false") {return ($directSql) ? $value : false;}
        elseif ($value === "true") {return ($directSql) ? $value : true;}
        else {
            self::generateRequiredError($attribute, $value);
        }
    }

    public static function convertToGuid($attribute, $value, $directSql = true)
    {
        if (preg_match(
            '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $value)) {
            if ($directSql) {
                return "UuidToBin('{$value}')";
            } else {
                return new Expression("UuidToBin('{$value}')");
            }
        } else {
            self::generateRequiredError($attribute, $value);
        }
    }

    public static function convertToTimestamp($attribute, $value, $directSql = true)
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/', $value)) {
            $value = str_replace("T", " ", $value);
        }
        elseif (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
            // do nothing
        }
        else {
            self::generateRequiredError($attribute, $value);
        }
        return ($directSql) ? "'{$value}'" : new \DateTime($value);
    }

    protected static function convertToDate($attribute, $value, $directSql = true)
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            // do nothing
        }
        else {
            self::generateRequiredError($attribute, $value);
        }
        return ($directSql) ? "'{$value}'" : strtotime($value);
    }

    protected static function convertToString($attribute, $value, $directSql = true)
    {
        if ($value === null) {
            self::generateRequiredError($attribute, $value);
        }
        return self::$schema->quoteValue($value);
    }

    protected static function convertToEnum($attribute, $value, $directSql = true)
    {
        if ($value === null) {
            self::generateRequiredError($attribute, $value);
        }
        return "'{$value}'";
    }

    protected static function convertToNumber($attribute, $value, $directSql = true)
    {
        if ($value === null) {
            self::generateRequiredError($attribute, $value);
        }
        return $value;
    }

    protected static function convertToBlob($attribute, $value, $directSql = true)
    {
        return "''";
    }

    protected static function convertToId($attribute, $value, $type)
    {
        $result = ['attr2Null' => false, 'extraRow' => false];
        if (isset(self::$searchMatrices[$attribute][$value])) {
            $result['value'] = self::$searchMatrices[$attribute][$value];
        } else {
            switch ($type) {
                case self::FK_STRICT:
                    self::generateRequiredError($attribute, $value);
                    break;
                case self::FK_SOFT:
                    $result['value'] = "null";
                    break;
                case self::FK_STRICT_NULL:
                    $result['attr2Null'] = true;
                    $result['value'] = "null";
                    if ($value !== '00000000-0000-0000-0000-000000000000') {
                        $result['extraRow'] = true;
                    }
                    break;
            }
        }
        return $result;
    }
}