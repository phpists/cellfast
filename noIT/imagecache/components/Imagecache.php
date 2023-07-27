<?php

namespace noIT\imagecache\components;

use Yii;
use yii\imagine\Image;
use yii\base\Component;
use Imagine\Imagick\Imagine;
use yii\base\ErrorException;

class Imagecache extends Component {

    public $driver = 'GD';

    /** @var $imagine Imagine */
    public $imagine;

    public $presets = [];

    public $rootPath;
    public $rootUrl;

    public $extensions = [
        'jpg' => 'jpeg',
        'jpeg' => 'jpeg',
        'png' => 'png',
        'gif' => 'gif',
        'bmp' => 'bmp',
        'webp' => 'webp'
    ];

    public function init()
    {
        parent::init();

        $this->imagine = Image::getImagine();
    }

    public function open($file = null, $driver = null) {
        if(empty($file) || !realpath($file)) {
            throw new ErrorException('File name can not be empty and exists');
        }

        return $this->imagine->open($file);
    }
}