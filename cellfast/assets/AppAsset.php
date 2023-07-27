<?php

namespace cellfast\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/cellfast.css?546575647566',
        'css/checkout.css?546575647566',
	    'css/calculator.css?546575647566',
	    'custom/fix.css?546575647566',
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
