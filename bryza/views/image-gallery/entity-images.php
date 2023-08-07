<?php

/** @var $this \yii\web\View */
/** @var $items \noIT\gallery\models\GalleryImage */
/** @var $limit integer */
/** @var $entity string */
/** @var $entity_id integer */
/** @var $params array */
?>

<?= $this->render('@common/widgets/views/image-gallery/entity-images', [
	'items' => $items,
	'limit' => $limit,
	'entity' => $entity,
	'entity_id' => $entity_id,
	'params' => $params,
]);
?>