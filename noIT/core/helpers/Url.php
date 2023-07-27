<?php

namespace noIT\core\helpers;

class Url extends \yii\helpers\Url {

	/**
	 * Стандартный хелпер Url::to пропускает все ссылки через urlencode
	 * иногда это не нужно (например, для формирования схем фильтров в URL)
	 * за это отвечает дополнительный параметр $encode
	 *
	 * @param string $url
	 * @param bool $scheme
	 * @param bool $encode
	 *
	 * @return string
	 */
    public static function to( $url = '', $scheme = false, $encode = true ) {
	    $url = parent::to( $url, $scheme );
	    if (!$encode) {
	    	$url = urldecode($url);
	    }
	    return $url;
    }
}