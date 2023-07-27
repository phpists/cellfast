<?php

namespace common\models;

use common\models\soap\E1cTypeOfPrice;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%product_price}}".
 *
 * @property integer $product_item_id
 * @property integer $price_type_id
 * @property double $price
 * @property double $common_price
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ProductItem $productItem
 * @property PriceType $priceType
 */
class ProductPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_price}}';
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
            [['product_item_id', 'price_type_id'], 'required'],
            [['product_item_id', 'price_type_id', 'created_at', 'updated_at'], 'integer'],
	        [['price', 'common_price'], 'filter', 'filter' => function ($value) {$value = str_replace([',', ' '], ['.', ' '], $value); return $value; }],
	        [['price', 'common_price'], 'number', 'min' => 0, 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
	        [['product_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductItem::className(), 'targetAttribute' => ['product_item_id' => 'id']],
	        [['price_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PriceType::className(), 'targetAttribute' => ['price_type_id' => 'id']],
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
    public function getPriceType()
    {
        return $this->hasOne(PriceType::className(), ['id' => 'price_type_id']);
    }
}
