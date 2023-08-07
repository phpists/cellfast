<?php

namespace bryza\assets;

use yii\web\AssetBundle;

/**
 * Frontpage pages frontend application asset bundle.
 */
class MainAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/main.css?546575647564',
    ];

    public $js = [];

    public $depends = [
        'bryza\assets\AppAsset',
    ];
}
