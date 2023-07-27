<?php

namespace common\models;

use noIT\gallery\models\GalleryImage;

class ProductImage extends GalleryImage
{
	public $uploadDir = '@cdn/{entity}/{entity_id}';
	public $uploadUrl = '@cdnUrl/{entity}/{entity_id}';

	public static function tableName()
	{
		return 'product_image';
	}
}