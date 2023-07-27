<?php
namespace backend\models;

use common\helpers\AdminHelper;
use common\models\ProductAvailability;
use common\models\ProductPrice;
use common\models\Warehouse;
use Yii;
use yii\db\Expression;

class ProductItem extends \common\models\ProductItem {
	public $productModelClass = 'backend\models\Product';
	public $typeModelClass;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge([
			'id' => Yii::t('app', 'ID'),
			'project_id' => Yii::t('app', 'Project'),
			'sku' => Yii::t('app', 'SKU'),
			'native_name' => Yii::t('app', 'Native Name'),
			'name' => Yii::t('app', 'Name'),
			'price' => Yii::t('app', 'Price'),
			'image' => Yii::t('app', 'Image'),
			'status' => Yii::t('app', 'Availability'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
		],
			AdminHelper::LangsFieldLabels('name', 'Name'),
			AdminHelper::LangsFieldLabels('teaser', 'Teaser'),
			AdminHelper::LangsFieldLabels('body', 'Body'),
			AdminHelper::LangsFieldLabels('seo_h1', 'Seo H1'),
			AdminHelper::LangsFieldLabels('seo_title', 'Seo title'),
			AdminHelper::LangsFieldLabels('seo_description', 'Seo description'),
			AdminHelper::LangsFieldLabels('seo_keywords', 'Seo keywords')
		);
	}
}