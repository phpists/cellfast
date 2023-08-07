<?php

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/** @var $this \yii\web\View */
/** @var $pagination \yii\data\Pagination */
/** @var $label string */
/** @var $page integer */
/** @var $class string */
/** @var $disabled boolean */
/** @var $active boolean */
/** @var $options array */
/** @var $pageCssClass string */
/** @var $activePageCssClass string */
/** @var $disabledPageCssClass array */
/** @var $disabledListItemSubTagOptions array */

$linkWrapTag = ArrayHelper::remove($options, 'tag', 'li');
Html::addCssClass($options, empty($class) ? $pageCssClass : $class);

if ($active) {
	Html::addCssClass($options, $activePageCssClass);
}
if ($disabled) {
	Html::addCssClass($options, $disabledPageCssClass);
	$tag = ArrayHelper::remove($disabledListItemSubTagOptions, 'tag', 'span');
} else {
	$linkOptions['data-page'] = $page;
}
?>
<?php if ($disabled) :?>
	<?= Html::tag($linkWrapTag, Html::tag($tag, $label, $disabledListItemSubTagOptions), $options)?>
<?php else :?>
	<?= Html::tag($linkWrapTag, Html::a($label, $pagination->createUrl($page), $linkOptions), $options)?>
<?php endif?>

