<?php

namespace noIT\imagecache\helpers;

use Codeception\Lib\ParamsLoader;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ManipulatorInterface;
use noIT\imagecache\components\Imagecache;
use Yii;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\imagine\Image;

class ImagecacheHelper extends BaseObject
{
    /** @var  Imagecache $imageDriver */
    private static $imageDriver;
    private static $presets;

    /** @return Imagecache */
    public static function getDriver()
    {
        if (empty(self::$imageDriver)) {
            self::$imageDriver = Yii::$app->imagecache;
        }
        return self::$imageDriver;
    }

    public static function getPreset($preset)
    {
        if (empty(self::$presets)) {
            self::$presets = self::getDriver()->presets;
        }
        return empty(self::$presets[$preset]) ? null : self::$presets[$preset];
    }

    public static function getImage($file, $preset, $imgOptions = [])
    {
        $preset_alias = is_array($preset) ? array_keys($preset)[0] : null;
        if (($src = self::getImageSrc($file, $preset, $preset_alias)) !== false) {
            return Html::img($src, $imgOptions);
        }
        return '';
    }

    public static function getImageSrc($file, $preset, $preset_alias = null)
    {
        if (is_string($preset)) {
            $preset_alias = $preset;
            $preset = self::getPreset($preset);
        }
        if (empty($preset) || empty($preset_alias)) {
            return $file;
        }
        $filePath = self::getPathFromUrl($file);

        if (!file_exists($filePath)) {
            return false;
        }
        if (!preg_match('#^(.*)\.(' . self::getExtensionsRegexp() . ')$#', $file, $matches)) {
            return $file;
        }
        return self::getPresetUrl($filePath, $preset, $preset_alias);
    }

    private static function getPathFromUrl($url)
    {
        return substr_replace($url, Yii::getAlias(self::getDriver()->rootPath), 0, strlen(Yii::getAlias(self::getDriver()->rootUrl)));
    }

    private static function getUrlFromPath($path)
    {
        return substr_replace($path, Yii::getAlias(self::getDriver()->rootUrl), 0, strlen(Yii::getAlias(self::getDriver()->rootPath)));
    }

    private static function mb_substr_replace($string, $replacement, $start, $length = 0)
    {
        $result = mb_substr($string, 0, $start, 'UTF-8');
        $result .= $replacement;

        if ($length > 0) {
            $result .= mb_substr($string, ($start + $length + 1), mb_strlen($string, 'UTF-8'), 'UTF-8');
        }

        return $result;
    }

    private static function getPresetUrl($filePath, $preset, $preset_alias)
    {
        setlocale(LC_CTYPE, 'ru_RU.UTF-8');
        $pathinfo = pathinfo($filePath);
        setlocale(LC_CTYPE, 'C');
        $presetPath = $pathinfo['dirname'] . '/styles/' . strtolower($preset_alias);
        $presetFilePath = $presetPath . '/' . $pathinfo['basename'];
        $presetUrl = self::getUrlFromPath($presetFilePath);
        if (file_exists($presetFilePath)) {
            return $presetUrl;
        }
        if (!file_exists($presetPath)) {
            @mkdir($presetPath, 0777, true);
        }
        return false;
    }

    private static function createPresetImage($filePath, $preset, $preset_alias)
    {
        try {
            $image = self::getDriver()->imagine->open($filePath);
            foreach ($preset as $action => $data) {
                switch ($action) {
                    case 'append':
                        if (($_image = self::getDriver()->imagine->open($data['src']))) {
                            $offset_x = empty($data['offset']['x']) ? 0 : $data['offset']['x'];
                            $offset_y = empty($data['offset']['y']) ? 0 : $data['offset']['y'];

                            if ($offset_x == 'center') {
                                $offset_x = floor($image->getSize()->getWidth() / 2 - $_image->getSize()->getWidth() / 2);
                            }
                            if ($offset_y == 'center') {
                                $offset_y = floor($image->getSize()->getHeight() / 2 - $_image->getSize()->getHeight() / 2);
                            }

                            $centerImage = new \Imagine\Image\Point($offset_x, $offset_y);

                            $image->paste($_image, $centerImage);

                            unset($_image);
                        }
                        break;
                    case 'prepend':
                        if (($_image = self::getDriver()->imagine->open($data['src']))) {
                            $offset_x = empty($data['offset']['x']) ? 0 : $data['offset']['x'];
                            $offset_y = empty($data['offset']['y']) ? 0 : $data['offset']['y'];

                            if ($offset_x == 'center') {
                                $offset_x = floor($_image->getSize()->getWidth() / 2 - $image->getSize()->getWidth() / 2);
                            }
                            if ($offset_y == 'center') {
                                $offset_y = floor($_image->getSize()->getHeight() / 2 - $image->getSize()->getHeight() / 2);
                            }

                            $centerImage = new \Imagine\Image\Point($offset_x, $offset_y);

                            $_image->paste($image, $centerImage);

                            return $_image;
                        }
                        break;
                    case 'canvas':
                        $width = !isset($data['width']) ? null : $data['width'];
                        $height = !isset($data['height']) ? null : $data['height'];
                        $size = new \Imagine\Image\Box($width, $height);

                        $offset_x = !isset($data['offset']['x']) ? 0 : $data['offset']['x'];
                        $offset_y = !isset($data['offset']['y']) ? 0 : $data['offset']['y'];

                        if ($offset_x == 'center') {
                            $offset_x = floor($width / 2 - $image->getSize()->getWidth() / 2);
                        }
                        if ($offset_y == 'center') {
                            $offset_y = floor($height / 2 - $image->getSize()->getHeight() / 2);
                        }

                        $centerImage = new \Imagine\Image\Point($offset_x, $offset_y);

                        $background_color = !isset($data['background']['color']) ? '#ffffff' : $data['background']['color'];
                        $background_alpha = !isset($data['background']['alpha']) ? 100 : $data['background']['alpha'];

                        $palette = new \Imagine\Image\Palette\RGB();
                        $color = $palette->color($background_color, $background_alpha);

                        $_image = clone $image;

                        $image = self::getDriver()->imagine->create($size, $color);
                        $image->paste($_image, $centerImage);

                        unset($_image);
                        break;
                    case 'resize':
                        $width = empty($data['width']) ? null : $data['width'];
                        $height = empty($data['height']) ? null : $data['height'];
                        $filter = empty($data['filter']) ? ImageInterface::FILTER_UNDEFINED : $data['filter'];
                        $image->resize(new Box($width, $height), $filter);
                    case 'thumbnail':
                        $width = empty($data['width']) ? null : $data['width'];
                        $height = empty($data['height']) ? null : $data['height'];
                        $mode = empty($data['mode']) ? ManipulatorInterface::THUMBNAIL_INSET : $data['mode'];
                        $filter = empty($data['filter']) ? ImageInterface::FILTER_UNDEFINED : $data['filter'];
                        $image = $image->thumbnail(new Box($width, $height), $mode, $filter);
                        break;
                    case 'crop':
                        $width = empty($data['width']) ? null : $data['width'];
                        $height = empty($data['height']) ? null : $data['height'];
                        $mode = empty($data['mode']) ? ManipulatorInterface::THUMBNAIL_OUTBOUND : $data['mode'];
                        $filter = empty($data['filter']) ? ImageInterface::FILTER_UNDEFINED : $data['filter'];
                        $image = $image->thumbnail(new Box($width, $height), $mode, $filter);
                        break;
                    default:
                        break;
                }
            }
            return $image;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Get extensions regexp
     * @return string regexp
     */
    private static function getExtensionsRegexp()
    {
        $keys = array_keys(self::getDriver()->extensions);
        return '(?i)' . join('|', $keys);
    }

    /**
     * Get size from suffix
     * @param string $suffix
     * @return string size
     */
    private function getSizeFromSuffix($suffix)
    {
        return array_search($suffix, $this->getSizeSuffixes());
    }

    /**
     * Get suffix from size
     * @param string $size
     * @return string suffix
     */
    private function getSufixFromSize($size)
    {
        return ArrayHelper::getValue($this->getSizeSuffixes(), $size);
    }

    private function getSizeSuffixes()
    {
        $suffixes = [];
        foreach ($this->sizes as $size => $sizeConf) {
            $suffixes[$size] = ArrayHelper::getValue($this->sizeSuffixes, $size, $this->defaultSizeSuffix . $size);
        }
        return $suffixes;
    }
}