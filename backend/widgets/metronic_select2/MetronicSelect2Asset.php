<?php
namespace backend\widgets\metronic_select2;

use kartik\select2\Select2Asset;

class MetronicSelect2Asset extends Select2Asset
{
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->setSourcePath('@vendor/kartik-v/yii2-widget-select2/assets');
		$this->setupAssets('js', ['js/select2.full', 'js/select2-krajee']);
		parent::init();
	}
}