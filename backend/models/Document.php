<?php
namespace backend\models;

use Yii;
use common\helpers\AdminHelper;

class Document extends \common\models\Document
{
	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'type' => 'Тип',
			AdminHelper::LangsFieldLabels('name', 'Name'),
			AdminHelper::LangsFieldLabels('name', 'Description'),
			'cover_image' => 'Картинка',
			'file' => 'Файл',
			'status' => Yii::t('app', 'Status'),
			'project_id' => 'Проект'
		];
	}
}