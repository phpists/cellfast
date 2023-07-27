<?php

namespace cellfast\assets;

use yii\web\AssetBundle;

/**
 * Partners page frontend application asset bundle.
 */
class PartnersAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
    	'css/jquery.mCustomScrollbar.min.css?546575647564',
        'css/buy.css?546575647564',
	    'css/online-stores.css?546575647564'
    ];

    public $js = [];

    public $depends = [
        'cellfast\assets\AppAsset',
    ];
}
