<?php

use yii\helpers\Url;

/** @var $this yii\web\View */
/** @var $active string */

$items = [
	'summary' => [
		'label' => Yii::t('app', 'Statistics'),
		'url' => Url::to(['e1c-binding/index'])
	],
	'good' => [
		'label' => Yii::t('app', 'Product items'),
		'url' => Url::to(['e1c-binding/good'])
	],
	'group' => [
		'label' => Yii::t('app', 'Product types'),
		'url' => Url::to(['e1c-binding/group'])
	],
];

foreach ($items as $key => &$item) {
	if ( $active === $key ) {
		$item['active'] = true;
	}
}

?>

<?= \yii\bootstrap\Tabs::widget([
	'items' => $items,
])?>
