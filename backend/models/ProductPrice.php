<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;

class ProductPrice extends \common\models\ProductPrice
{
	/**
	 * Related models
	 */
	public $priceTypeModelClass = 'backend\models\PriceType';
	public $productPriceModelClass = 'backend\models\ProductPrice';

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'product_item_id' => Yii::t('app', 'Product Item ID'),
			'price_type_id' => Yii::t('app', 'Price Type ID'),
			'price' => Yii::t('app', 'Price'),
			'common_price' => Yii::t('app', 'Common Price'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
		];
	}
}