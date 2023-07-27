<?php

namespace common\models\soap;

use Yii;

/**
 * This is the model class for table "{{%e1c_print_form_of_order}}".
 *
 * @property integer $order_head_id
 * @property string $order_head
 * @property string $part_number
 * @property string $part_description
 * @property string $part_type
 * @property string $part_print_form_eds
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cHeadOfOrder $e1cHeadOfOrderGuid
 * @property E1cHeadOfOrder $e1cHeadOfOrder
 */
class E1cPrintFormOfOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_print_form_of_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_head_id', 'order_head', 'part_number', 'part_description', 'part_type', 'part_print_form_eds'], 'required'],
            [['order_head_id'], 'integer'],
            [['part_type', 'part_print_form_eds'], 'string'],
            [['timestamp', 'created_at', 'updated_at'], 'safe'],
            [['order_head', 'part_number'], 'string', 'max' => 16],
            [['part_description'], 'string', 'max' => 50],
            [['order_head'], 'exist', 'skipOnError' => true, 'targetClass' => E1cHeadOfOrder::className(), 'targetAttribute' => ['order_head' => 'guid']],
            [['order_head_id'], 'exist', 'skipOnError' => true, 'targetClass' => E1cHeadOfOrder::className(), 'targetAttribute' => ['order_head_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_head_id' => Yii::t('app', 'Order Head ID'),
            'order_head' => Yii::t('app', 'Order Head'),
            'part_number' => Yii::t('app', 'Part Number'),
            'part_description' => Yii::t('app', 'Part Description'),
            'part_type' => Yii::t('app', 'Part Type'),
            'part_print_form_eds' => Yii::t('app', 'Part Print Form Eds'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cHeadOfOrderGuid()
    {
        return $this->hasOne(E1cHeadOfOrder::className(), ['guid' => 'order_head']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cHeadOfOrder()
    {
        return $this->hasOne(E1cHeadOfOrder::className(), ['id' => 'order_head_id']);
    }
}