<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%product_availability}}".
 *
 * @property integer $product_item_id
 * @property integer $warehouse_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ProductItem $productItem
 * @property Warehouse $warehouse
 */
class ProductAvailability extends \yii\db\ActiveRecord
{
	const STATUS_UNAVAILABLE = 0;
	const STATUS_INSTOCK = 10;
	const STATUS_ENDS = 20;
	const STATUS_COMING = 30;
	const STATUS_PREORDER = 40;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_availability}}';
    }

    public function behaviors() {
	    return [
		    'timestamp' => [
			    'class' => TimestampBehavior::className(),
		    ],
	    ];
    }

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_item_id', 'warehouse_id'], 'required'],
            [['status', 'product_item_id', 'warehouse_id', 'created_at', 'updated_at'], 'integer'],
	        [['status'], 'default', 'value' => self::STATUS_UNAVAILABLE],
	        [['product_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductItem::className(), 'targetAttribute' => ['product_item_id' => 'id']],
	        [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::className(), 'targetAttribute' => ['warehouse_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductItem()
    {
        return $this->hasOne(ProductItem::className(), ['id' => 'product_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::className(), ['id' => 'warehouse_id']);
    }

	public static function statusLabels() {
		return [
			self::STATUS_UNAVAILABLE => Yii::t('app', 'Unavailable'),
			self::STATUS_INSTOCK => Yii::t('app', 'In stock'),
			self::STATUS_ENDS => Yii::t('app', 'Ends'),
			self::STATUS_COMING => Yii::t('app', 'Coming'),
			self::STATUS_PREORDER => Yii::t('app', 'Preorder'),
		];
	}

	public static function getDefault() {
		return self::STATUS_UNAVAILABLE;
	}
}
