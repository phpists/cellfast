<?php

namespace common\models\soap;

use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;

/**
 * This is the model class for table "{{%e1c_requisite_of_agreement}}".
 *
 * @property integer $agreement_id
 * @property string $agreement
 * @property integer $group_of_good_id
 * @property string $group_of_good
 * @property integer $type_of_price_id
 * @property string $type_of_price
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cAgreement $e1cAgreementGuid
 * @property E1cAgreement $e1cAgreement
 * @property E1cGroupOfGood $e1cGroupOfGoodGuid
 * @property E1cGroupOfGood $e1cGroupOfGood
 * @property E1cTypeOfPrice $e1cTypeOfPriceGuid
 * @property E1cTypeOfPrice $e1cTypeOfPrice
 */
class E1cRequisiteOfAgreement extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_requisite_of_agreement}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'agreement' => self::TYPE_GUID, 'group_of_good' => self::TYPE_GUID, 'type_of_price' => self::TYPE_GUID,
                'timestamp' => self::TYPE_TIMESTAMP
            ],
            'foreignKeys' => [
                'agreement' => [
                    'class' => E1cAgreement::className(),
                    'targetField' => 'agreement_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
                'group_of_good' => [
                    'class' => E1cGroupOfGood::className(),
                    'targetField' => 'group_of_good_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
                'type_of_price' => [
                    'class' => E1cTypeOfPrice::className(),
                    'targetField' => 'type_of_price_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
            ],
            'updatable' => true,
            'primaryKey' => ['agreement' => self::TYPE_GUID, 'group_of_good' => self::TYPE_GUID],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'agreement_id' => Yii::t('app', 'Agreement ID'),
            'agreement' => Yii::t('app', 'Agreement'),
            'group_of_good_id' => Yii::t('app', 'Group Of Good ID'),
            'group_of_good' => Yii::t('app', 'Group Of Good'),
            'type_of_price_id' => Yii::t('app', 'Type Of Price ID'),
            'type_of_price' => Yii::t('app', 'Type Of Price'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cAgreementGuid()
    {
        return $this->hasOne(E1cAgreement::className(), ['guid' => 'agreement']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cAgreement()
    {
        return $this->hasOne(E1cAgreement::className(), ['id' => 'agreement_id']);
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