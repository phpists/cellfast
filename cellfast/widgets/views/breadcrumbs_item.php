<?php

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

/** @var $this \yii\web\View */
/** @var $link array */
/** @var $encodeLabels boolean */
/** @var $template string */

?>
<?php
$encodeLabel = ArrayHelper::remove($link, 'encode', $encodeLabels);
if (array_key_exists('label', $link)) {
	$label = $encodeLabel ? Html::encode($link['label']) : $link['label'];
} else {
	throw new InvalidConfigException('The "label" element is required for each link.');
}
if (isset($link['template'])) {
	$template = $link['template'];
}
if (isset($link['url'])) {
	$options = $link;
	unset($options['template'], $options['label'], $options['url']);
	$link = Html::a($label, $link['url'], $options);
} else {
	$link = $label;
}
?>
<?= strtr($template, ['{link}' => $link])?>