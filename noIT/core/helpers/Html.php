<?php

namespace noIT\core\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Html extends \yii\helpers\Html
{
	public static function a( $text, $url = null, $options = [] )
	{
		if ($url !== null) {
			$options['href'] = Url::to($url);
			if (isset($options['encode'])) {
				if ($options['encode'] === false) {
					$options['href'] = urldecode($options['href']);
				}
				unset($options['encode']);
			}
		}

		return static::tag('a', $text, $options);
	}

	public static function getImagesFromContent($content, $attributes = ['src'])
	{

		$images = [];
		$imageAttributes = [];
		$newImages = [];

		// get all images
		preg_match_all('/<img[^>]+>/i', $content, $images);

		if (!($images = array_shift($images))) {
			return [];
		}

		// get image attributes
		foreach($images as $k => $image) {
			$newImages[$k]['original'] = $image;
			foreach($attributes as $param) {
				preg_match('/('. $param .')=("[^"]*"|\'[^\']*\')/', $image,$imageAttributes[$k][$param]);
			}
		}

		// clean array
		foreach($imageAttributes as $k => $item) {
			foreach($attributes as $param) {

				if(count($item[$param]) === 3) {
					$newImages[$k][$param] = str_replace('"', '', $item[$param][2]);
				}

			}

		}

		return $newImages;
	}

	public static function replaceImages($content, $view, $attributes = ['src'])
	{
		$images = self::getImagesFromContent($content, $attributes);

		$replacements = [];

		foreach ($images as $i => $data) {
			$replacements[$i] = Yii::$app->view->render($view, ['data' => $data]);
		}

		return str_replace(ArrayHelper::getColumn($images, 'original'), $replacements, $content);
	}
}