<?php
namespace bryza\widgets;

use common\helpers\SiteHelper;
use common\models\AboutMainPage;
use yii\base\Widget;

class AboutUsWidget extends Widget
{
	public function run()
	{

		$model = AboutMainPage::find()
		                      ->where([
			                      'project_id' => SiteHelper::getProject()
		                      ])
		                      ->one();

		return $this->render('about-us', ['model' => $model]);
	}
}