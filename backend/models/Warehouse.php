<?php

namespace backend\models;

use Yii;

class Warehouse extends \common\models\Warehouse {

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'native_name' => Yii::t('app', 'Native Name'),
			'location_place_id' => Yii::t('app', 'Location Place ID'),
			'lng' => Yii::t('app', 'Lng'),
			'lat' => Yii::t('app', 'Lat'),
			'address_ru_ru' => Yii::t('app', 'Address Ru Ru'),
			'address_uk_ua' => Yii::t('app', 'Address Uk Ua'),
			'sort_order' => Yii::t('app', 'Sort Order'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
		];
	}
}