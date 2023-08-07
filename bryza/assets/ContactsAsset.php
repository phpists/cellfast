<?php

namespace bryza\assets;

use yii\web\AssetBundle;

/**
 * Contacts page frontend application asset bundle.
 */
class ContactsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/contacts.css?546575647564',
    ];
    public $js = [

    ];
    public $depends = [
        'bryza\assets\MainAsset',
        'bryza\assets\AppAsset',
    ];
}
