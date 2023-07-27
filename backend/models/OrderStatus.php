<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;

class OrderStatus extends \common\models\OrderStatus
{
	/**
	 * Related models
	 */
	public $orderModelClass = 'backend\models\Order';

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'native_name' => Yii::t('app', 'Native Name'),
			'cancel' => Yii::t('app', 'Cancel'),
			'accept' => Yii::t('app', 'Accept'),
			'success' => Yii::t('app', 'Success'),
			'e1c_slug' => Yii::t('app', 'E1c Slug'),
			'sort_order' => Yii::t('app', 'Sort Order'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
		];
	}
}