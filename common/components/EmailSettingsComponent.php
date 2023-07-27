<?php

namespace common\components;

use Yii;
use \yii\base\Component;

class EmailSettingsComponent extends Component
{
	public static $SECTION = 'EmailSettings';

	public function getEmailAttribute($project = null)
	{
		if ($project === null) {
			$project = Yii::$app->projects->current->alias;
		}
		return 'email__' . $project;
	}

	public function getEmailLikeArray( $attribute, $separator = ',', $section = null)
	{
		if(!$section) {
			$section = self::$SECTION;
		}

		$value = Yii::$app->settings->get($attribute, $section);

		if(strpos($value, $separator)) {
			$value = explode($separator, $value);
			return $value;
		} else {
			return [$value];
		}

	}

}