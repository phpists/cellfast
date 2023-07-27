<?php

namespace cellfast\assets;

/**
 * LightBox plugin - frontend application asset bundle.
 */
class LightBoxAsset extends AppAsset
{
	public $basePath = '@webroot';

	public $baseUrl = '@web';

	public $css = [
		'custom/lightbox/css/lightbox.min.css?887614',
	];

	public $js = [
		'custom/lightbox/js/lightbox.min.js?887614',
	];

	public $depends = [
		'cellfast\assets\AppAsset',
	];
}