<?php

namespace common\models;

use common\helpers\AdminHelper;
use voskobovich\linker\LinkerBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "delivery".
 *
 * @property integer $id
 * @property string $native_name
 * @property integer $is_self
 * @property string $name_ru_ru
 * @property string $name_uk_ua
 * @property string $description_ru_ru
 * @property string $description_uk_ua
 * @property integer $sort_order
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Warehouse[] $warehouses
 * @property Order[] $orders
 * @property Payment[] $payments
 */
class Delivery extends ActiveRecord {
	const STATUS_ACTIVE = 10;

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		$behaviors = [
			'timestamp' => [
				'class' => TimestampBehavior::className(),
			],
			'relations' => [
				'class' => LinkerBehavior::className(),
				'relations' => [
					'rel_warehouses' => 'warehouses',
				],
			],
		];

		return $behaviors;
	}

	public static function tableName() {
		return '{{%delivery}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['native_name'], 'required'],
			[['native_name'], 'string', 'max' => 150],
			[['is_self', 'sort_order', 'status', 'created_at', 'updated_at'], 'integer'],
			[AdminHelper::LangsField(['name']), 'string', 'max' => 150],
			[AdminHelper::LangsField(['description']), 'string'],
			[['rel_warehouses'], 'each', 'rule' => ['integer']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrders()
	{
		return $this->hasMany(Order::className(), ['delivery_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getWarehouses()
	{
		return $this->hasMany(Warehouse::className(), ['id' => 'warehouse_id'])
		            ->viaTable('{{%delivery_has_warehouse}}', ['delivery_id' => 'id']);
	}

	public function getPayments()
	{
		return $this->hasMany(Payment::className(), ['id' => 'payment_id'])
		            ->viaTable('{{%payment_has_delivery}}', ['delivery_id' => 'id']);
	}
}