<?php

namespace common\models\soap;

use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;

/**
 * This is the model class for table "{{%e1c_availability_of_good}}".
 *
 * @property integer $good_id
 * @property string $good
 * @property string $angle
 * @property integer $warehouse_id
 * @property string $warehouse
 * @property string $status
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cGood $e1cGoodGuid
 * @property E1cGood $e1cGood
 * @property E1cWarehouse $e1cWarehouseGuid
 * @property E1cWarehouse $e1cWarehouse
 */
class E1cAvailabilityOfGood extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_availability_of_good}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'good' => self::TYPE_GUID, 'angle' => self::TYPE_NUMBER, 'warehouse' => self::TYPE_GUID,
                'status' => self::TYPE_ENUM, 'timestamp' => self::TYPE_TIMESTAMP
            ],
            'foreignKeys' => [
                'good' => [
                    'class' => E1cGood::className(),
                    'targetField' => 'good_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
                'warehouse' => [
                    'class' => E1cWarehouse::className(),
                    'targetField' => 'warehouse_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
            ],
            'updatable' => false,
            'primaryKey' => ['good' => self::TYPE_GUID, 'warehouse' => self::TYPE_GUID],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'good_id' => Yii::t('app', 'Good ID'),
            'good' => Yii::t('app', 'Good'),
            'angle' => Yii::t('app', 'Angle'),
            'warehouse_id' => Yii::t('app', 'Warehouse ID'),
            'warehouse' => Yii::t('app', 'Warehouse'),
            'status' => Yii::t('app', 'Status'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGoodGuid()
    {
        return $this->hasOne(E1cGood::className(), ['guid' => 'good']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGood()
    {
        return $this->hasOne(E1cGood::className(), ['id' => 'good_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cWarehouseGuid()
    {
        return $this->hasOne(E1cWarehouse::className(), ['guid' => 'warehouse']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cWarehouse()
    {
        return $this->hasOne(E1cWarehouse::className(), ['id' => 'warehouse_id']);
    }
}