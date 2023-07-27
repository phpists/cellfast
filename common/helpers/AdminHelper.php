<?php
namespace common\helpers;

use dosamigos\selectize\SelectizeDropDownList;
use Yii;
use yii\helpers\ArrayHelper;

class AdminHelper extends \noIT\core\helpers\AdminHelper
{
	public static function getProjectWidget($form, $model, $fieldName = 'project_id', $multiple = false)
	{
		$params = [
			'placeholder' => Yii::t('app', 'Select project')
		];

		if($multiple) {
			$params['multiple'] = true;
		}

		$data = ArrayHelper::map(Yii::$app->projects->projects, 'alias', 'name');

		return self::getSelectWidget($form, $model, $fieldName, $data, $params);
	}

}
?>
