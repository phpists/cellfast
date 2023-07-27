<?php

namespace common\models\soap;

use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;

/**
 * This is the model class for table "{{%e1c_price}}".
 *
 * @property integer $good_id
 * @property string $good
 * @property integer $type_of_price_id
 * @property string $type_of_price
 * @property string $value
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cGood $e1cGoodGuid
 * @property E1cGood $e1cGood
 * @property E1cTypeOfPrice $e1cTypeOfPriceGuid
 * @property E1cTypeOfPrice $e1cTypeOfPrice
 */
class E1cPrice extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_price}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'good' => self::TYPE_GUID, 'type_of_price' => self::TYPE_GUID, 'value' => self::TYPE_NUMBER,
                'timestamp' => self::TYPE_TIMESTAMP
            ],
            'foreignKeys' => [
                'good' => [
                    'class' => E1cGood::className(),
                    'targetField' => 'good_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
                'type_of_price' => [
                    'class' => E1cTypeOfPrice::className(),
                    'targetField' => 'type_of_price_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
            ],
            'updatable' => false,
            'primaryKey' => ['good' => self::TYPE_GUID, 'type_of_price' => self::TYPE_GUID],
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
            'type_of_price_id' => Yii::t('app', 'Type Of Price ID'),
            'type_of_price' => Yii::t('app', 'Type Of Price'),
            'value' => Yii::t('app', 'Value'),
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
    public function getE1cTypeOfPriceGuid()
    {
        return $this->hasOne(E1cTypeOfPrice::className(), ['guid' => 'type_of_price']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cTypeOfPrice()
    {
        return $this->hasOne(E1cTypeOfPrice::className(), ['id' => 'type_of_price_id']);
    }
}