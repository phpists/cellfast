<?php

namespace ines\assets;

use yii\web\AssetBundle;

/**
 * Catalog pages frontend application asset bundle.
 */
class CatalogAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/catalog.css',
    ];
    public $js = [
    	'js/filters/sidebar.js',
    ];
    public $depends = [
        'ines\assets\MainAsset',
        'ines\assets\AppAsset',
    ];
}
