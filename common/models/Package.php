<?php

namespace common\models;

use common\helpers\AdminHelper;
use voskobovich\linker\LinkerBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "package".
 *
 * @property integer $id
 * @property string $native_name
 * @property string $e1c_slug
 * @property string $name
 * @property string $caption
 * @property integer $sort_order
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ProductType[] $productTypes
 */
class Package extends ActiveRecord {
	const STATUS_ACTIVE = 10;

	public static function tableName() {
		return '{{%package}}';
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
					'rel_product_types' => 'productTypes',
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
			[['native_name'], 'string', 'max' => 150],
			[['caption_ru_ru', 'caption_uk_ua'], 'string'],
			[['sort_order', 'status', 'created_at', 'updated_at'], 'integer'],
			[['e1c_slug'], 'string', 'max' => 20],
			[AdminHelper::LangsField(['name']), 'string', 'max' => 150],
			[AdminHelper::LangsField(['caption']), 'string'],
			[['rel_product_types'], 'each', 'rule' => ['integer']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProductTypes()
	{
		return $this->hasMany(ProductType::className(), ['id' => 'product_type_id'])
			->viaTable('{{%product_type_has_package}}', ['package_id' => 'id']);
	}
}