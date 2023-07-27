<?php

namespace common\widgets;

use noIT\gallery\widgets\GalleryWidget;

class ImageGallery extends GalleryWidget {
	public $view = 'image-gallery/entity-images';

	public function init() {
		parent::init();

		if (!isset($this->params['image']['preset'])) {
			$this->params['image']['preset'] = 'entity_image_gallery';
		}
		if (!isset($this->params['image']['options'])) {
			$this->params['image']['options'] = [
				'class' => 'entity-image-gallery'
			];
		}
	}
}