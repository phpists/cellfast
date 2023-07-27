<?php

namespace cellfast\widgets;

use common\helpers\SiteHelper;
use common\models\Product;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class FeatureTableWidget extends Widget
{
	/** @var $model Product */
	public $model;

	public $showFeatures = true;

	public $showProperties = true;

	public $showHeader = false;

	public function run()
	{
		$data = [];

		if ($this->showFeatures) {
			$data = $this->model->featuresTable;
		}

		if ($this->showProperties) {
			$data = array_merge($data, $this->model->propertiesTable);
		}

		if ( !$data ) {
			return;
		}

		return $this->render('feature-table', [
			'model' => $this->model,
			'data' => $data,
			'showHeader' => $this->showHeader,
		]);
	}
}