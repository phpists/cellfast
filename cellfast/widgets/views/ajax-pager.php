<?php

/** @var $this \yii\web\View */
/** @var \yii\data\Pagination $pagination */
/** @var string $listView */
/** @var string $url */
/** @var string $someMoreCaption */
/** @var string $wrapper */

$this->render('@common/widgets/views/ajax-pager', [
	'pagination' => $pagination,
	'url' => $url,
	'wrapper' => $wrapper,
	'listView' => $listView,
	'someMoreCaption' => $someMoreCaption,
]);