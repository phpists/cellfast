<?php

namespace backend\models;

use common\helpers\AdminHelper;
use Yii;

class Package extends \common\models\Package {

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge([
			'id' => Yii::t('app', 'ID'),
			'native_name' => Yii::t('app', 'Native Name'),
			'e1c_slug' => Yii::t('app', 'E1c Slug'),
			'sort_order' => Yii::t('app', 'Sort order'),
			'status' => Yii::t('app', 'Status'),
			'created_at' => Yii::t('app', 'Created At'),
			'updated_at' => Yii::t('app', 'Updated At'),
		],
			AdminHelper::LangsFieldLabels('name', 'Name'),
			AdminHelper::LangsFieldLabels('caption', 'Caption')
		);
	}
}