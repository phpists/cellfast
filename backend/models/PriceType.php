<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;

class PriceType extends \common\models\PriceType
{
	/**
	 * Related models
	 */
	public $productPriceModelClass = 'backend\models\ProductPrice';

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge([
			'id' => Yii::t('app', 'ID'),
			'native_name' => Yii::t('app', 'Native Name'),
			'includeVAT' => Yii::t('app', 'Include VAT'),
			'sort_order' => Yii::t('app', 'Sort Order'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
		],
			AdminHelper::LangsFieldLabels('name', 'Name')
		);
	}
}