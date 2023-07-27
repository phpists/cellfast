<?php

namespace common\models\soap;

use Yii;

/**
 * This is the model class for table "{{%e1c_head_of_order}}".
 *
 * @property integer $id
 * @property string $guid
 * @property integer $agreement_id
 * @property string $agreement
 * @property integer $warehouse_id
 * @property string $warehouse
 * @property string $description_1c
 * @property integer $self_delivery
 * @property string $info_delivery
 * @property string $status
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cCommentOfOrder[] $e1cCommentOfOrdersGuid
 * @property E1cCommentOfOrder[] $e1cCommentOfOrders
 * @property E1cGoodOfOrder[] $e1cGoodOfOrdersGuid
 * @property E1cGoodOfOrder[] $e1cGoodOfOrders
 * @property E1cAgreement $e1cAgreementGuid
 * @property E1cAgreement $e1cAgreement
 * @property E1cWarehouse $e1cWarehouseGuid
 * @property E1cWarehouse $e1cWarehouse
 * @property E1cPrintFormOfOrder[] $e1cPrintFormOfOrdersGuid
 * @property E1cPrintFormOfOrder[] $e1cPrintFormOfOrders
 */
class E1cHeadOfOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_head_of_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['guid', 'agreement_id', 'agreement', 'warehouse_id', 'warehouse', 'description_1c', 'self_delivery', 'info_delivery', 'status'], 'required'],
            [['agreement_id', 'warehouse_id', 'self_delivery'], 'integer'],
            [['info_delivery', 'status'], 'string'],
            [['timestamp', 'created_at', 'updated_at'], 'safe'],
            [['guid', 'agreement', 'warehouse'], 'string', 'max' => 16],
            [['description_1c'], 'string', 'max' => 100],
            [['guid'], 'unique'],
            [['agreement'], 'exist', 'skipOnError' => true, 'targetClass' => E1cAgreement::className(), 'targetAttribute' => ['agreement' => 'guid']],
            [['agreement_id'], 'exist', 'skipOnError' => true, 'targetClass' => E1cAgreement::className(), 'targetAttribute' => ['agreement_id' => 'id']],
            [['warehouse'], 'exist', 'skipOnError' => true, 'targetClass' => E1cWarehouse::className(), 'targetAttribute' => ['warehouse' => 'guid']],
            [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => E1cWarehouse::className(), 'targetAttribute' => ['warehouse_id' => 'id']],
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
            'agreement_id' => Yii::t('app', 'Agreement ID'),
            'agreement' => Yii::t('app', 'Agreement'),
            'warehouse_id' => Yii::t('app', 'Warehouse ID'),
            'warehouse' => Yii::t('app', 'Warehouse'),
            'description_1c' => Yii::t('app', 'Description 1c'),
            'self_delivery' => Yii::t('app', 'Self Delivery'),
            'info_delivery' => Yii::t('app', 'Info Delivery'),
            'status' => Yii::t('app', 'Status'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cCommentOfOrdersGuid()
    {
        return $this->hasMany(E1cCommentOfOrder::className(), ['order_head' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cCommentOfOrders()
    {
        return $this->hasMany(E1cCommentOfOrder::className(), ['order_head_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGoodOfOrdersGuid()
    {
        return $this->hasMany(E1cGoodOfOrder::className(), ['order_head' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGoodOfOrders()
    {
        return $this->hasMany(E1cGoodOfOrder::className(), ['order_head_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cPrintFormOfOrdersGuid()
    {
        return $this->hasMany(E1cPrintFormOfOrder::className(), ['order_head' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cPrintFormOfOrders()
    {
        return $this->hasMany(E1cPrintFormOfOrder::className(), ['order_head_id' => 'id']);
    }
}