<?php
namespace backend\widgets\metronic_select2;

use kartik\base\AssetBundle;
use kartik\select2\ThemeDefaultAsset;

class MetronicThemeDefaultAsset extends AssetBundle
{
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		$this->js = [];
		$this->css = [];
	}
}