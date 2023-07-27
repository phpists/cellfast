<?php

namespace cellfast\widgets\assets\relatedContent;

use yii\web\AssetBundle;

/**
 * Content-item pages frontend application asset bundle.
 */
class RelatedContentStaticAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
//		'css/related_static.css',
	];
	public $js = [
	];
	public $depends = [
		'cellfast\widgets\assets\relatedContent\RelatedContentAsset',
	];
}
