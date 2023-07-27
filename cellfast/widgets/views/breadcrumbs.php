<?php

use yii\bootstrap\Html;

/** @var $this \yii\web\View */
/** @var $links array */
/** @var $homeLink string|array */
/** @var $encodeLabels boolean */
/** @var $itemTemplate string */
/** @var $activeItemTemplate string */
/** @var $options array */
/** @var $tag string */

?>
<?php
$resultLinks = [];
if ($homeLink === null) {
	$encodeLabels = false;
	$resultLinks[] = $this->render('breadcrumbs_item', [
		'link' => [
			'label' => \Yii::t('app', 'Homepage'), // '<i class="glyphicon glyphicon-home"></i>',
			'url' => Yii::$app->homeUrl,
			'class' => 'home'
		],
		'template' => $itemTemplate,
		'encodeLabels' => $encodeLabels,
	]);
} elseif ($homeLink !== false) {
	$resultLinks[] = $this->render('breadcrumbs_item', [
		'link' => $homeLink,
		'template' => $itemTemplate,
		'encodeLabels' => $encodeLabels
	]);
}

foreach ($links as $link) {
	if (!is_array($link)) {
		$link = ['label' => $link];
	}

	$resultLinks[] = $this->render('breadcrumbs_item', [
		'link' => $link,
		'template' => isset($link['url']) ? $itemTemplate : $activeItemTemplate,
		'encodeLabels' => $encodeLabels
	]);
}


?>
<?= Html::tag('div', Html::tag($tag, implode('', $resultLinks), $options), ['class' => 'breadcrumbs'])?>
