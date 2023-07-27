<?php

namespace common\models\soap;

use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;

/**
 * This is the model class for table "{{%e1c_receivable}}".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $client
 * @property integer $agreement_id
 * @property string $agreement
 * @property integer $order_head_id
 * @property string $order_head
 * @property string $order_view
 * @property string $shipping_date
 * @property string $invoice_view
 * @property string $deadline_for_payment
 * @property string $sum_of_invoice
 * @property string $sum_of_debt
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cAgreement $e1cAgreementGuid
 * @property E1cAgreement $e1cAgreement
 * @property E1cClient $e1cClientGuid
 * @property E1cClient $e1cClient
 */
class E1cReceivable extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_receivable}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'client' => self::TYPE_GUID, 'agreement' => self::TYPE_GUID, 'order_head' => self::TYPE_GUID,
                'order_view' => self::TYPE_STRING, 'shipping_date' => self::TYPE_DATE, 'invoice_view' => self::TYPE_STRING,
                'deadline_for_payment' => self::TYPE_DATE, 'sum_of_invoice' => self::TYPE_NUMBER,
                'sum_of_debt' => self::TYPE_NUMBER, 'timestamp' => self::TYPE_TIMESTAMP
            ],
            'foreignKeys' => [
                'client' => [
                    'class' => E1cClient::className(),
                    'targetField' => 'client_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
                'agreement' => [
                    'class' => E1cAgreement::className(),
                    'targetField' => 'agreement_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
                'order_head' => [
                    'class' => E1cHeadOfOrder::className(),
                    'targetField' => 'order_head_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_SOFT
                ],
            ],
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
            'client_id' => Yii::t('app', 'Client ID'),
            'client' => Yii::t('app', 'Client'),
            'agreement_id' => Yii::t('app', 'Agreement ID'),
            'agreement' => Yii::t('app', 'Agreement'),
            'order_head_id' => Yii::t('app', 'Order Head ID'),
            'order_head' => Yii::t('app', 'Order Head'),
            'order_view' => Yii::t('app', 'Order View'),
            'shipping_date' => Yii::t('app', 'Shipping Date'),
            'invoice_view' => Yii::t('app', 'Invoice View'),
            'deadline_for_payment' => Yii::t('app', 'Deadline For Payment'),
            'sum_of_invoice' => Yii::t('app', 'Sum Of Invoice'),
            'sum_of_debt' => Yii::t('app', 'Sum Of Debt'),
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
}