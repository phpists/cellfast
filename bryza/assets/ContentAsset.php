<?php

namespace bryza\assets;

use yii\web\AssetBundle;

/**
 * Content-item pages frontend application asset bundle.
 */
class ContentAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/content.css?546575647564',
    ];

    public $js = [];

    public $depends = [
        'bryza\assets\AppAsset',
    ];
}
