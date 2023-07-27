<?php
namespace backend\assets;

/**
 * LightBox plugin - frontend application asset bundle.
 */
class LightBoxAsset extends AppAsset
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';

	public $css = [
		'custom/lightbox/css/lightbox.min.css?7571',
		'custom/lightbox/css/admin.min.css?7571',
	];

	public $js = [
		'custom/lightbox/js/lightbox.min.js?7571',
	];

	public $depends = [
		'backend\assets\AppAsset',
	];
}