<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%product_package}}".
 *
 * @property integer $product_item_id
 * @property integer $package_id
 * @property double $quantity
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ProductItem $productItem
 * @property Package $package
 */
class ProductPackage extends \yii\db\ActiveRecord
{
	/**
	 * Related models
	 */
	public $priceTypeModelClass = 'backend\models\PriceType';
	public $productPriceModelClass = 'backend\models\ProductPrice';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_package}}';
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
            [['product_item_id', 'package_id'], 'required'],
            [['product_item_id', 'package_id', 'created_at', 'updated_at'], 'integer'],
	        [['quantity'], 'filter', 'filter' => function ($value) {$value = str_replace([',', ' '], ['.', ' '], $value); return $value; }],
	        [['quantity'], 'number', 'min' => 0, 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
	        [['product_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductItem::className(), 'targetAttribute' => ['product_item_id' => 'id']],
	        [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => Package::className(), 'targetAttribute' => ['package_id' => 'id']],
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
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['id' => 'package_id']);
    }
}
