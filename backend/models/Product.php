<?php
namespace backend\models;

use common\helpers\AdminHelper;
use common\models\ProductProperty;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class Product extends \common\models\Product {
	/**
	 * Related models
	 */
    public $typeModelClass = 'backend\models\ProductType';
    public $itemModelClass = 'backend\models\ProductItem';

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge([
			'id' => Yii::t('app', 'ID'),
			'type_id' => Yii::t('app', 'Product type'),
			'project_id' => Yii::t('app', 'Project'),
			'native_name' => Yii::t('app', 'Native Name'),
			'slug' => Yii::t('app', 'Slug'),
			'image' => Yii::t('app', 'Image'),
			'items' => Yii::t('app', 'Product items'),
			'status' => Yii::t('app', 'Status'),
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

	public function getItemsDataProvider() {
		return new ActiveDataProvider([
			'query' => ProductItem::find()->where(['product_id' => $this->id]),
			'pagination' => [
				'pageSize' => null
			],
		]);
	}


}