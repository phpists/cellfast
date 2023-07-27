<?php

namespace common\models\soap;

use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use noIT\soap\SoapServerModule as SOAP;
use Yii;
use yii\db\Expression;
use yii\db\Transaction;

/**
 * This is the model class for table "{{%e1c_session}}".
 *
 * @property integer $id
 * @property integer $guid
 * @property string $entity
 * @property integer $size
 * @property string $status
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 */
class E1cSession extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_session}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'guid' => self::TYPE_GUID, 'entity' => self::TYPE_ENUM, 'size' => self::TYPE_NUMBER,
                'status' => self::TYPE_ENUM,'timestamp' => self::TYPE_TIMESTAMP
            ],
            'foreignKeys' => [],
            'updatable' => false,
            'primaryKey' => ['id' => self::TYPE_NUMBER],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'guid' => Yii::t('app', 'UUID'),
            'entity' => Yii::t('app', 'Entity'),
            'size' => Yii::t('app', 'Size'),
            'status' => Yii::t('app', 'Status'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function loadAndSave($timePoint, $rows)
    {
        $date_range = date_diff(date_create(), $timePoint);
        if (!($date_range->i < 5 && $date_range->h = 0 && $date_range->days = 0)) {
//            throw new \ErrorException("A significant deviation of time was revealed: 
//            {$date_range->format('%y Year %m Month %d Day %h Hours %i Minute %s Seconds')}");
        }
        $db = self::getDb();
        if (!isset(self::$schema)) {
            self::$schema = $db->getSchema();
        }
        $guidSession = (new \yii\db\Query())
            ->select(new Expression('UUID() AS uuid'))
            ->one();
        if (!is_array($guidSession)) {
            throw new \ErrorException("Error during generation UUID");
        }
        $guidSession = array_shift($guidSession);
        array_walk(
            $rows,
                function (&$item, $key, $guidSession){
                    $item['guid'] = $guidSession;
                    $item['status'] = 'W';
                },
                $guidSession);
        $tableName = self::quoteName(self::$schema->getRawTableName(self::tableName()));
        self::$tableLayout = self::getTableLayout();
        $transaction = $db->beginTransaction(Transaction::SERIALIZABLE);
        try {
            $unfinishedRows = self::findAll(['status' => 'W']);
            foreach ($unfinishedRows as $unfinishedRow) {
                $unfinishedRow->status = 'F';
                $unfinishedRow->save(false);
            }

            $buffer = [];
            $rowKeys = '';
            foreach ($rows as $row) {
                if (empty($rowKeys)) {
                    $rowKeys = array_flip(array_keys($row));
                }
                $buffer[] = self::prepareSql($row, $rowKeys, $tableName);
            }
            if (!empty($buffer)) {
                $db->createCommand(implode("; ", $buffer))->execute();
            }
        } catch (\ErrorException $e) {
            $transaction->rollBack();
            throw $e;
        }
        $transaction->commit();

        return $guidSession;
    }

    /**
     * @inheritdoc
     * @return boolean
     */
    public static function checkQueue($tableName)
    {
        $entityName = array_search($tableName, SOAP::ENTITY_NAMES_MAP, true);
        if (!$entityName) {return false;}
        $findedRow = self::find()->where(['status' => 'W'])->orderBy('id')->limit(1)->one();
        if ($findedRow === null) {return false;}
        if ($findedRow->entity !== $entityName) {return false;}
        return true;
    }

    /**
     * @inheritdoc
     * @return boolean
     */
    public static function shiftQueue($tableName, $duration)
    {
        $entityName = array_search($tableName, SOAP::ENTITY_NAMES_MAP, true);
        if (!$entityName) {return false;}
        $findedRow = self::find()->where(['status' => 'W', 'entity' => $entityName])->one();
        if ($findedRow === null) {return false;}
        $findedRow->status = 'E';
        $findedRow->duration = $duration;
        $findedRow->save(false);
        return true;
    }
}