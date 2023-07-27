<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;

class ProductPackage extends \common\models\ProductPackage
{
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'product_item_id' => Yii::t('app', 'Product Item'),
			'package_id' => Yii::t('app', 'Package'),
			'quantity' => Yii::t('app', 'Quantity'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
		];
	}
}