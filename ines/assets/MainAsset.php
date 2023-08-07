<?php

namespace ines\assets;

use yii\web\AssetBundle;

/**
 * Frontpage pages frontend application asset bundle.
 */
class MainAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/main.css',
    ];
    public $js = [

    ];
    public $depends = [
        'ines\assets\AppAsset',
    ];
}
