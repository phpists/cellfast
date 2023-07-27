<?php

namespace common\models\soap;

use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;

/**
 * This is the model class for table "{{%e1c_warehouse}}".
 *
 * @property integer $id
 * @property string $guid
 * @property string $name
 * @property string $address
 * @property integer $mark_deleted
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cAvailabilityOfGood[] $e1cAvailabilityOfGoodsGuid
 * @property E1cAvailabilityOfGood[] $e1cAvailabilityOfGoods
 * @property E1cHeadOfOrder[] $e1cHeadOfOrdersGuid
 * @property E1cHeadOfOrder[] $e1cHeadOfOrders
 */
class E1cWarehouse extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_warehouse}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'guid' => self::TYPE_GUID, 'name' => self::TYPE_STRING, 'address' => self::TYPE_STRING,
                'mark_deleted' => self::TYPE_BOOLEAN, 'timestamp' => self::TYPE_TIMESTAMP
            ],
            'foreignKeys' => [],
            'updatable' => true,
            'primaryKey' => ['guid' => self::TYPE_GUID],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'guid' => Yii::t('app', 'Guid'),
            'name' => Yii::t('app', 'Name'),
            'address' => Yii::t('app', 'Address'),
            'mark_deleted' => Yii::t('app', 'Mark Deleted'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cAvailabilityOfGoodsGuid()
    {
        return $this->hasMany(E1cAvailabilityOfGood::className(), ['warehouse' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cAvailabilityOfGoods()
    {
        return $this->hasMany(E1cAvailabilityOfGood::className(), ['warehouse_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cHeadOfOrdersGuid()
    {
        return $this->hasMany(E1cHeadOfOrder::className(), ['warehouse' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cHeadOfOrders()
    {
        return $this->hasMany(E1cHeadOfOrder::className(), ['warehouse_id' => 'id']);
    }
}