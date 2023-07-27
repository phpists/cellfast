<?php
namespace common\models\settings;

use Yii;
use yii\base\Model;

abstract class Settings extends Model
{
	protected function unserializeAttribute($attribute, $section)
	{
		$settings = Yii::$app->settings;

		if( ($list = unserialize($settings->get($attribute, $section))) ) {
			return $list;
		} else {
			return [];
		}
	}

	protected function serializeAttribute($attribute, $section)
	{
		$settings = Yii::$app->settings;

		$settings->set($attribute, serialize($this[$attribute]), $section, 'string');
	}
}