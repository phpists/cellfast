<?php

namespace common\models\soap;

use Yii;

/**
 * This is the model class for table "{{%e1c_good_of_order}}".
 *
 * @property integer $order_head_id
 * @property string $order_head
 * @property integer $good_id
 * @property string $good
 * @property string $angle
 * @property string $quantity
 * @property string $sum
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cGood $e1cGoodGuid
 * @property E1cGood $e1cGood
 * @property E1cHeadOfOrder $e1cHeadOfOrderGuid
 * @property E1cHeadOfOrder $e1cHeadOfOrder
 */
class E1cGoodOfOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_good_of_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_head_id', 'order_head', 'good_id', 'good', 'angle', 'quantity', 'sum'], 'required'],
            [['order_head_id', 'good_id'], 'integer'],
            [['angle', 'quantity', 'sum'], 'number'],
            [['timestamp', 'created_at', 'updated_at'], 'safe'],
            [['order_head', 'good'], 'string', 'max' => 16],
            [['good'], 'exist', 'skipOnError' => true, 'targetClass' => E1cGood::className(), 'targetAttribute' => ['good' => 'guid']],
            [['good_id'], 'exist', 'skipOnError' => true, 'targetClass' => E1cGood::className(), 'targetAttribute' => ['good_id' => 'id']],
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
            'good_id' => Yii::t('app', 'Good ID'),
            'good' => Yii::t('app', 'Good'),
            'angle' => Yii::t('app', 'Angle'),
            'quantity' => Yii::t('app', 'Quantity'),
            'sum' => Yii::t('app', 'Sum'),
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