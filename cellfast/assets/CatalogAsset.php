<?php

namespace cellfast\assets;

use yii\web\AssetBundle;

/**
 * Catalog pages frontend application asset bundle.
 */
class CatalogAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/catalog.css?546575647564',
    ];

    public $js = [
    	'js/filters/sidebar.js',
    ];

    public $depends = [
        'cellfast\assets\MainAsset',
        'cellfast\assets\AppAsset',
    ];
}
