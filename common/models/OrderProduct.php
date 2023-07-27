<?php
namespace common\models;

use yii\db\ActiveRecord;

class OrderProduct extends ActiveRecord {
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_product}}';
    }
}