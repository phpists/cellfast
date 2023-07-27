<?php
namespace common\widgets;

use common\models\Banner;
use common\models\Category;
use Yii;

class CatalogWidget extends \yii\bootstrap\Widget
{
	public function run()
	{
		parent::run();

		$categories = Yii::$app->category->getRootCategories();

		if($categories) {
			return $this->render('catalog_categories', ['items' => $categories]);
		}
	}
}
