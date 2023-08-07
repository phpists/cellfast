<?php

namespace bryza\assets;

use yii\web\AssetBundle;

/**
 * Content-item pages frontend application asset bundle.
 */
class ProductAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/product.css?546575647564',
    ];

    public $js = [
//    	'js/product/options.js',
    	'js/bootstrap/dropdown.js',
    	'js/plugins/bootstrap-select.js',
    	'js/product/card.js',
    ];

    public $depends = [
        'bryza\assets\AppAsset',
    ];
}
