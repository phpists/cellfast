<?php

namespace cellfast\assets;

use yii\web\AssetBundle;

/**
 * Content-item pages frontend application asset bundle.
 */
class ContentsAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/contents.css?546575647564',
    ];

    public $js = [];

    public $depends = [
        'cellfast\assets\AppAsset',
    ];
}
