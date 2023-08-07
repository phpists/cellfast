<?php

namespace bryza\widgets\assets\relatedContent;

use yii\web\AssetBundle;

/**
 * Content-item pages frontend application asset bundle.
 */
class RelatedContentSliderAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
//		'css/related_slider.css',
	];
	public $js = [
	];
	public $depends = [
		'bryza\widgets\assets\relatedContent\RelatedContentAsset',
	];
}
