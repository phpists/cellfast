<?php

namespace noIT\gallery\widgets;

use yii\base\Widget;
use noIT\gallery\models\GalleryImage;

class GalleryWidget extends Widget {
	/**
	 * Gallery items (GalleryImage fow example)
	 * @var GalleryImage[] $items
	 */
	public $items;

	public $entity;

	public $entity_id;

	public $entityModel = 'noIT\gallery\models\GalleryImage';

	public $params = [];

	/**
	 * Max count of items
	 * @var integer|null $count
	 */
	public $limit;

	public $view = 'default-gallery';

	public function run() {
		if (null === $this->items && null !== $this->entity && null !== $this->entityModel) {
			$this->items = call_user_func([$this->entityModel, 'findByEntity'], $this->entity, $this->entity_id, $this->limit)->all();
		}

		if (!$this->items) {
			return;
		}

		return $this->render($this->view, [
			'limit' => $this->limit,
			'items' => $this->items,
			'entity' => $this->entity,
			'entity_id' => $this->entity_id,
			'params' => $this->params,
		]);
	}
}