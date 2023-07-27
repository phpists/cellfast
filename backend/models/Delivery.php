<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;

class Delivery extends \common\models\Delivery {
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge([
			'id' => Yii::t('app', 'ID'),
			'native_name' => Yii::t('app', 'Native Name'),
			'is_self' => Yii::t('app', 'Is self'),
			'sort_order' => Yii::t('app', 'Sort order'),
			'status' => Yii::t('app', 'Status'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
			'rel_warehouses' => Yii::t('app', 'Warehouses'),
		],
			AdminHelper::LangsFieldLabels('name', 'Name'),
			AdminHelper::LangsFieldLabels('description', 'Description')
		);
	}
}