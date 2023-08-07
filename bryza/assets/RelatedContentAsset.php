<?php

namespace bryza\assets;

use yii\web\AssetBundle;

/**
 * Content-item pages frontend application asset bundle.
 */
class RelatedContentAsset extends AssetBundle
{
	public $basePath = '@webroot';

	public $baseUrl = '@web';

	public $css = [
		'css/related.css?546575647564',
	];

	public $js = [
		'js/plugins/slick.min.js',
	];

	public $depends = [
	];
}
