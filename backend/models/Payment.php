<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;

class Payment extends \common\models\Payment {

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge([
			'id' => Yii::t('app', 'ID'),
			'native_name' => Yii::t('app', 'Native Name'),
			'VAT' => Yii::t('app', 'VAT'),
			'sort_order' => Yii::t('app', 'Sort Order'),
			'status' => Yii::t('app', 'Status'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
			'rel_deliveries' => Yii::t('app', 'Deliveries'),
		],
			AdminHelper::LangsFieldLabels('name', 'Name'),
			AdminHelper::LangsFieldLabels('description', 'Description')
		);
	}
}