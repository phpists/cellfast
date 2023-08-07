<?php

namespace ines\assets;

use yii\web\AssetBundle;

/**
 * GdsCalc widget frontend application asset bundle.
 */
class GdsCalcAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/gdscalc.css?123',
    ];

    public $js = [
    	'js/gdscalc.js?123',
    ];

    public $depends = [
        'bryza\assets\CatalogAsset',
        'bryza\assets\MainAsset',
        'bryza\assets\AppAsset',
    ];
}
