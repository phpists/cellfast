<?php

namespace cellfast\assets;

use yii\web\AssetBundle;

/**
 * Frontpage pages frontend application asset bundle.
 */
class MainAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/main.css?546575647565',
    ];

    public $js = [];

    public $depends = [
        'cellfast\assets\AppAsset',
    ];
}
