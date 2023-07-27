<?php

namespace common\models;

use common\helpers\AdminHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Warehouse
 * @package common\models
 *
 * @property integer $id
 * @property string $native_name
 * @property integer $location_country_id
 * @property integer $location_region_id
 * @property integer $location_place_id
 * @property double $lng
 * @property double $lat
 * @property string $address_ru_ru
 * @property string $address_uk_ua
 * @property integer $sort_order
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ProductAvailability[] $productAvailabilities
 *
 * @property LocationCountry $locationCountry
 * @property LocationRegion $locationRegion
 * @property LocationPlace $locationPlace
 */
class Warehouse extends ActiveRecord {
	public static function tableName() {
		return '{{%warehouse}}';
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
		];

		return $behaviors;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['location_place_id', 'sort_order', 'created_at', 'updated_at'], 'integer'],
			[['lng', 'lat'], 'number'],
			[['created_at', 'updated_at'], 'required'],
			[['native_name'], 'string', 'max' => 150],
			[AdminHelper::LangsField(['address']), 'string'],
		];
	}

	public function getLocationRegion() {
		return $this->hasOne(LocationRegion::className(), ['id' => 'location_region_id']);
	}

	public function getLocationCountry() {
		return $this->hasOne(LocationCountry::className(), ['id' => 'location_country_id']);
	}

	public function getLocationPlace() {
		return $this->hasOne(LocationPlace::className(), ['id' => 'location_place_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProductAvailabities()
	{
		return $this->hasMany(ProductAvailability::className(), ['warehouse_id' => 'id']);
	}

	public function beforeSave( $insert ) {
		if ( null !== $this->location_place_id && ( $place = $this->locationPlace ) ) {
			if ( null === $this->location_region_id ) {
				$this->location_region_id = $place->region_id;
			}
			if ( null !== $this->location_region_id && ( $region = $this->locationRegion ) ) {
				$this->location_country_id = $region->country_id;
			}
		}
		return parent::beforeSave( $insert );
	}
}