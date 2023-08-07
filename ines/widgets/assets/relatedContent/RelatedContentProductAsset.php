<?php

namespace ines\widgets\assets\relatedContent;

use yii\web\AssetBundle;

/**
 * Content-item pages frontend application asset bundle.
 */
class RelatedContentProductAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/product.css',
	];
	public $js = [
	];
	public $depends = [
		'ines\widgets\assets\relatedContent\RelatedContentAsset',
	];
}
