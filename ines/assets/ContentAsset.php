<?php

namespace ines\assets;

use yii\web\AssetBundle;

/**
 * Content-item pages frontend application asset bundle.
 */
class ContentAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/content.css',
    ];
    public $js = [

    ];
    public $depends = [
        'ines\assets\AppAsset',
    ];
}
