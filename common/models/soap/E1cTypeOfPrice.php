<?php

namespace common\models\soap;

use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;

/**
 * This is the model class for table "{{%e1c_type_of_price}}".
 *
 * @property integer $id
 * @property string $guid
 * @property string $name
 * @property integer $include_vat
 * @property integer $mark_deleted
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cAgreement[] $e1cAgreementsGuid
 * @property E1cAgreement[] $e1cAgreements
 * @property E1cPrice[] $e1cPricesGuid
 * @property E1cPrice[] $e1cPrices
 * @property E1cGood[] $goods
 */
class E1cTypeOfPrice extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_type_of_price}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'guid' => self::TYPE_GUID, 'name' => self::TYPE_STRING, 'include_vat' => self::TYPE_BOOLEAN,
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
            'include_vat' => Yii::t('app', 'Include Vat'),
            'mark_deleted' => Yii::t('app', 'Mark Deleted'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cAgreementsGuid()
    {
        return $this->hasMany(E1cAgreement::className(), ['type_of_price' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cAgreements()
    {
        return $this->hasMany(E1cAgreement::className(), ['type_of_price_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cPricesGuid()
    {
        return $this->hasMany(E1cPrice::className(), ['type_of_price' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cPrices()
    {
        return $this->hasMany(E1cPrice::className(), ['type_of_price_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(E1cGood::className(), ['id' => 'good_id'])->viaTable('{{%e1c_price}}', ['type_of_price_id' => 'id']);
    }
}