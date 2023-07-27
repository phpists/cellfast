<?php

namespace common\models\soap;

use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;

/**
 * This is the model class for table "{{%e1c_agreement}}".
 *
 * @property integer $id
 * @property string $guid
 * @property integer $client_id
 * @property string $client
 * @property string $name
 * @property string $valid_to
 * @property integer $mark_deleted
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cClient $e1cClientGuid
 * @property E1cClient $e1cClient
 * @property E1cHeadOfOrder[] $e1cHeadOfOrdersGuid
 * @property E1cHeadOfOrder[] $e1cHeadOfOrders
 * @property E1cReceivable[] $e1cReceivablesGuid
 * @property E1cReceivable[] $e1cReceivables
 * @property E1cRequisiteOfAgreement[] $e1cRequisiteOfAgreementsGuid
 * @property E1cRequisiteOfAgreement[] $e1cRequisiteOfAgreements
 */
class E1cAgreement extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_agreement}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'guid' => self::TYPE_GUID, 'client' => self::TYPE_GUID, 'name' => self::TYPE_STRING,
                'valid_to' => self::TYPE_DATE, 'mark_deleted' => self::TYPE_BOOLEAN, 'timestamp' => self::TYPE_TIMESTAMP
            ],
            'foreignKeys' => [
                'client' => [
                    'class' => E1cClient::className(),
                    'targetField' => 'client_id', 'refField' => 'id', 'searchField' => 'guid',
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
            'client_id' => Yii::t('app', 'Client ID'),
            'client' => Yii::t('app', 'Client'),
            'name' => Yii::t('app', 'Name'),
            'valid_to' => Yii::t('app', 'Valid To'),
            'mark_deleted' => Yii::t('app', 'Mark Deleted'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cClientGuid()
    {
        return $this->hasOne(E1cClient::className(), ['guid' => 'client']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cClient()
    {
        return $this->hasOne(E1cClient::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cHeadOfOrdersGuid()
    {
        return $this->hasMany(E1cHeadOfOrder::className(), ['agreement' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cHeadOfOrders()
    {
        return $this->hasMany(E1cHeadOfOrder::className(), ['agreement_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cReceivablesGuid()
    {
        return $this->hasMany(E1cReceivable::className(), ['agreement' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cReceivables()
    {
        return $this->hasMany(E1cReceivable::className(), ['agreement_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cRequisiteOfAgreementsGuid()
    {
        return $this->hasMany(E1cRequisiteOfAgreement::className(), ['agreement' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cRequisiteOfAgreements()
    {
        return $this->hasMany(E1cRequisiteOfAgreement::className(), ['agreement_id' => 'id']);
    }
}