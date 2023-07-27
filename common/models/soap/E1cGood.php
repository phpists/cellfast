<?php

namespace common\models\soap;

use common\models\ProductItem;
use common\models\ProductItemEntity;
use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;

/**
 * This is the model class for table "{{%e1c_good}}".
 *
 * @property integer $id
 * @property string $guid
 * @property string $name
 * @property string $name_full
 * @property string $name_polish
 * @property string $unit_of_measure
 * @property integer $packing_ratio
 * @property integer $pallet_ratio
 * @property string $weight
 * @property string $volume
 * @property integer $oversized
 * @property integer $random_angle
 * @property integer $group_of_good_id
 * @property string $group_of_good
 * @property string $code_vendor
 * @property integer $code_ucgfea_id
 * @property string $code_ucgfea
 * @property string $picture
 * @property integer $mark_deleted
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cAvailabilityOfGood[] $e1cAvailabilityOfGoodsGuid
 * @property E1cAvailabilityOfGood[] $e1cAvailabilityOfGoods
 * @property E1cCodeUcgfea $e1cCodeUcgfeaGuid
 * @property E1cCodeUcgfea $e1cCodeUcgfea
 * @property E1cGroupOfGood $e1cGroupOfGoodGuid
 * @property E1cGroupOfGood $e1cGroupOfGood
 * @property E1cGoodOfOrder[] $e1cGoodOfOrdersGuid
 * @property E1cGoodOfOrder[] $e1cGoodOfOrders
 * @property E1cPrice[] $e1cPricesGuid
 * @property E1cPrice[] $e1cPrices
 * @property E1cTypeOfPrice[] $typeOfPrices
 * @property ProductItemEntity[] $appEntity
 */
class E1cGood extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_good}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'guid' => self::TYPE_GUID, 'name' => self::TYPE_STRING, 'name_full' => self::TYPE_STRING,
                'name_polish' => self::TYPE_STRING, 'unit_of_measure' => self::TYPE_STRING,
                'packing_ratio' => self::TYPE_NUMBER, 'pallet_ratio' => self::TYPE_NUMBER, 'weight' => self::TYPE_NUMBER,
                'volume' => self::TYPE_NUMBER, 'oversized' => self::TYPE_BOOLEAN, 'random_angle' => self::TYPE_BOOLEAN,
                'group_of_good' => self::TYPE_GUID, 'code_vendor' => self::TYPE_STRING, 'code_ucgfea' => self::TYPE_GUID,
                'picture' => self::TYPE_BLOB, 'mark_deleted' => self::TYPE_BOOLEAN, 'timestamp' => self::TYPE_TIMESTAMP
            ],
            'foreignKeys' => [
                'group_of_good' => [
                    'class' => E1cGroupOfGood::className(),
                    'targetField' => 'group_of_good_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
                'code_ucgfea' => [
                    'class' => E1cCodeUcgfea::className(),
                    'targetField' => 'code_ucgfea_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
            ],
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
            'name_full' => Yii::t('app', 'Name Full'),
            'name_polish' => Yii::t('app', 'Name Polish'),
            'unit_of_measure' => Yii::t('app', 'Unit Of Measure'),
            'packing_ratio' => Yii::t('app', 'Packing Ratio'),
            'pallet_ratio' => Yii::t('app', 'Pallet Ratio'),
            'weight' => Yii::t('app', 'Weight'),
            'volume' => Yii::t('app', 'Volume'),
            'oversized' => Yii::t('app', 'Oversized'),
            'random_angle' => Yii::t('app', 'Random Angle'),
            'group_of_good_id' => Yii::t('app', 'Group Of Good ID'),
            'group_of_good' => Yii::t('app', 'Group Of Good'),
            'code_vendor' => Yii::t('app', 'Code Vendor'),
            'code_ucgfea_id' => Yii::t('app', 'Code Ucgfea ID'),
            'code_ucgfea' => Yii::t('app', 'Code Ucgfea'),
            'picture' => Yii::t('app', 'Picture'),
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
        return $this->hasMany(E1cAvailabilityOfGood::className(), ['good' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cAvailabilityOfGoods()
    {
        return $this->hasMany(E1cAvailabilityOfGood::className(), ['good_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cCodeUcgfeaGuid()
    {
        return $this->hasOne(E1cCodeUcgfea::className(), ['guid' => 'code_ucgfea']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cCodeUcgfea()
    {
        return $this->hasOne(E1cCodeUcgfea::className(), ['id' => 'code_ucgfea_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGroupOfGoodGuid()
    {
        return $this->hasOne(E1cGroupOfGood::className(), ['guid' => 'group_of_good']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGroupOfGood()
    {
        return $this->hasOne(E1cGroupOfGood::className(), ['id' => 'group_of_good_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGoodOfOrdersGuid()
    {
        return $this->hasMany(E1cGoodOfOrder::className(), ['good' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGoodOfOrders()
    {
        return $this->hasMany(E1cGoodOfOrder::className(), ['good_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cPricesGuid()
    {
        return $this->hasMany(E1cPrice::className(), ['good' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cPrices($type_of_price_ids = [])
    {
        $query = $this->hasMany(E1cPrice::className(), ['good_id' => 'id']);
        if ($type_of_price_ids) {
            $query->where(['type_of_price_id' => $type_of_price_ids]);
        }
        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeOfPrices()
    {
        return $this->hasMany(E1cTypeOfPrice::className(), ['id' => 'type_of_price_id'])->viaTable('{{%e1c_price}}', ['good_id' => 'id']);
    }

    public function getAppEntity() {
    	return $this->hasOne(ProductItem::className(), ['e1c_id' => 'id']);
    }
}