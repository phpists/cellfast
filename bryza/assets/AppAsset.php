<?php

namespace bryza\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/bryza.css?130',
        'css/checkout.css?130',
        'css/calculator.css?130',
        'css/gdsdraincalc.css?130',
        'custom/fix.css?130',
        'custom/new-for-bryza.css?130',
    ];

    public $js = [
        'js/bootstrap/modal.js',
        'js/plugins/slick.min.js',
        'js/plugins/modernizr.js',
        'js/plugins/jquery.validate.min.js',
        'js/plugins/jquery.mask.min.js',
        'js/plugins/jquery.blueimp-gallery.min.js',
        'js/plugins/jquery.mCustomScrollbar.min.js',
        '//developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js',
        'cart' => 'js/cart.js?130',
        'js/plugins/google-map.js',
        '//unpkg.com/sweetalert/dist/sweetalert.min.js?130',
        'js/custom.js?131',
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
