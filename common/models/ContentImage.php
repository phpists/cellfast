<?php

namespace common\models;

use noIT\gallery\models\GalleryImage;

class ContentImage extends GalleryImage {

	public static function tableName() {
		return 'content_image';
	}
}