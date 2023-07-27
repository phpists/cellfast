<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;

class Order extends \common\models\Order
{
	/**
	 * Related models
	 */
	public $orderStatusModelClass = 'backend\models\OrderStatus';
	public $orderProductModelClass = 'backend\models\OrderProduct';
	public $warehouseModelClass = 'backend\models\Warehouse';
	public $userModelClass = 'common\models\User';

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'status_id' => Yii::t('app', 'Status'),
			'user_id' => Yii::t('app', 'User'),
			'warehouse_id' => Yii::t('app', 'Warehouse'),
			'delivery_id' => Yii::t('app', 'Delivery'),
			'payment_id' => Yii::t('app', 'Payment'),
			'number' => Yii::t('app', 'Number'),
			'token' => Yii::t('app', 'Token'),
			'is_quick' => Yii::t('app', 'Is Quick'),
			'order_comment' => Yii::t('app', 'Order Comment'),
			'delivery_comment' => Yii::t('app', 'Delivery Comment'),
			'delivery_data' => Yii::t('app', 'Delivery Data'),
			'delivery_cost' => Yii::t('app', 'Delivery Cost'),
			'payment_comment' => Yii::t('app', 'Payment Comment'),
			'payment_data' => Yii::t('app', 'Payment Data'),
			'payment_cost' => Yii::t('app', 'Payment Cost'),
			'discount_abs' => Yii::t('app', 'Discount Abs'),
			'discount_percent' => Yii::t('app', 'Discount Percent'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
		];
	}
}