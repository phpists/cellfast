<?php

namespace common\models;

use common\helpers\AdminHelper;
use voskobovich\linker\LinkerBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property string $native_name
 * @property double $VAT
 * @property string $name_ru_ru
 * @property string $name_uk_ua
 * @property string $description_ru_ru
 * @property string $description_uk_ua
 * @property integer $sort_order
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Order[] $orders
 * @property Delivery[] $deliveries
 */
class Payment extends ActiveRecord {
	const STATUS_ACTIVE = 10;

	public static function tableName() {
		return '{{%payment}}';
	}

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
					'rel_deliveries' => 'deliveries',
				],
			],
		];

		return $behaviors;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['native_name'], 'required'],
			[['VAT'], 'number'],
			[['sort_order', 'status', 'created_at', 'updated_at'], 'integer'],
			[['VAT'], 'default', 'value' => 0],
			[['sort_order'], 'default', 'value' => 0],
			[['status'], 'default', 'value' => self::STATUS_ACTIVE],
			[['native_name'], 'string', 'max' => 150],
			[AdminHelper::LangsField(['name']), 'string', 'max' => 150],
			[AdminHelper::LangsField(['description']), 'string'],
			[['rel_deliveries'], 'each', 'rule' => ['integer']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getOrders()
	{
		return $this->hasMany(Order::className(), ['payment_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDeliveries()
	{
		return $this->hasMany(Delivery::className(), ['id' => 'delivery_id'])
		            ->viaTable('{{%payment_has_delivery}}', ['payment_id' => 'id']);
	}
}