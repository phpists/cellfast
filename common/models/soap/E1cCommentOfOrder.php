<?php

namespace common\models\soap;

use Yii;

/**
 * This is the model class for table "{{%e1c_comment_of_order}}".
 *
 * @property integer $id
 * @property integer $order_head_id
 * @property string $order_head
 * @property string $user
 * @property string $comment
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cHeadOfOrder $e1cHeadOfOrderGuid
 * @property E1cHeadOfOrder $e1cHeadOfOrder
 */
class E1cCommentOfOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_comment_of_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_head_id', 'order_head', 'user', 'comment'], 'required'],
            [['id', 'order_head_id'], 'integer'],
            [['comment'], 'string'],
            [['timestamp', 'created_at', 'updated_at'], 'safe'],
            [['order_head'], 'string', 'max' => 16],
            [['user'], 'string', 'max' => 50],
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
            'id' => Yii::t('app', 'ID'),
            'order_head_id' => Yii::t('app', 'Order Head ID'),
            'order_head' => Yii::t('app', 'Order Head'),
            'user' => Yii::t('app', 'User'),
            'comment' => Yii::t('app', 'Comment'),
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