<?php

namespace ines\assets;

use yii\web\AssetBundle;

/**
 * Content-item pages frontend application asset bundle.
 */
class ContentsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/contents.css',
    ];
    public $js = [

    ];
    public $depends = [
        'ines\assets\AppAsset',
    ];
}
