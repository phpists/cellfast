<?php
namespace backend\models;

use Yii;
use common\helpers\AdminHelper;

class Partner extends \common\models\Partner
{
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'type' => 'Тип',
			'location_region_id' => 'Location Region ID',
			AdminHelper::LangsFieldLabels('name', 'Name'),
			AdminHelper::LangsFieldLabels('caption', 'Description'),
			'address' => 'Address',
			'coordinate' => 'Coordinate',
			'phones' => 'Phones',
			'website' => 'Website',
			'logotype' => 'Logotype',
			'email' => 'Email',
			'status' => Yii::t('app', 'Status'),
			'projects' => 'Проекты',
		];
	}
}