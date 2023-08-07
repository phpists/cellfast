<?php

use yii\bootstrap\Html;

/** @var $this \yii\web\View */
/** @var $pagination \yii\data\Pagination */
/** @var $pageCount integer */
/** @var $currentPage integer */
/** @var $firstPageLabel string */
/** @var $prevPageLabel string */
/** @var $lastPageLabel string */
/** @var $nextPageLabel string */
/** @var $pageRange integer */
/** @var $firstPageCssClass string */
/** @var $prevPageCssClass string */
/** @var $nextPageCssClass string */
/** @var $lastPageCssClass string */
/** @var $options array */
/** @var $pageCssClass string */
/** @var $activePageCssClass string */
/** @var $disabledPageCssClass string */
/** @var $disabledListItemSubTagOptions string */
/** @var $disableCurrentPageButton string */


$buttons = [];

// first page
if ($firstPageLabel !== false) {
	$buttons[] = $this->render('button', [
		'label' => $firstPageLabel,
		'page' => 0,
		'class' => $firstPageCssClass,
		'disabled' => $currentPage <= 0,
		'active' => false,
		'pageCssClass' => $pageCssClass,
		'activePageCssClass' => $activePageCssClass,
		'disabledPageCssClass' => $disabledPageCssClass,
		'disabledListItemSubTagOptions' => $disabledListItemSubTagOptions,
		'pagination' => $pagination,
	]);
}

// prev page
if ($prevPageLabel !== false) {
	if (($page = $currentPage - 1) < 0) {
		$page = 0;
	}
	$buttons[] = $this->render('button', [
		'label' => $prevPageLabel,
		'page' => $page,
		'class' => $prevPageCssClass,
		'disabled' => $currentPage <= 0,
		'active' => false,
		'pageCssClass' => $pageCssClass,
		'activePageCssClass' => $activePageCssClass,
		'disabledPageCssClass' => $disabledPageCssClass,
		'disabledListItemSubTagOptions' => $disabledListItemSubTagOptions,
		'pagination' => $pagination,
	]);
}

// internal pages
list($beginPage, $endPage) = $pageRange;
for ($i = $beginPage; $i <= $endPage; ++$i) {
	$buttons[] = $this->render('button', [
		'label' => $i + 1,
		'page' => $i,
		'class' => null,
		'disabled' => $disableCurrentPageButton && $i == $currentPage,
		'active' => $i == $currentPage,
		'pageCssClass' => $pageCssClass,
		'activePageCssClass' => $activePageCssClass,
		'disabledPageCssClass' => $disabledPageCssClass,
		'disabledListItemSubTagOptions' => $disabledListItemSubTagOptions,
		'pagination' => $pagination,
	]);
}

// next page
if ($nextPageLabel !== false) {
	if (($page = $currentPage + 1) >= $pageCount - 1) {
		$page = $pageCount - 1;
	}
	$buttons[] = $this->render('button', [
		'label' => $nextPageLabel,
		'page' => $page,
		'class' => $nextPageCssClass,
		'disabled' => $currentPage >= $pageCount - 1,
		'active' => false,
		'pageCssClass' => $pageCssClass,
		'activePageCssClass' => $activePageCssClass,
		'disabledPageCssClass' => $disabledPageCssClass,
		'disabledListItemSubTagOptions' => $disabledListItemSubTagOptions,
		'pagination' => $pagination,
	]);
}

// last page

if ($lastPageLabel !== false) {
	$buttons[] = $this->render('button', [
		'label' => $lastPageLabel,
		'page' => $pageCount - 1,
		'class' => $lastPageCssClass,
		'disabled' => $currentPage >= $pageCount - 1,
		'active' => false,
		'pageCssClass' => $pageCssClass,
		'activePageCssClass' => $activePageCssClass,
		'disabledPageCssClass' => $disabledPageCssClass,
		'disabledListItemSubTagOptions' => $disabledListItemSubTagOptions,
		'pagination' => $pagination,
	]);
}

$tag = \yii\helpers\ArrayHelper::remove($options, 'tag', 'ul');
?>
<?= Html::tag($tag, implode("\n", $buttons), $options)?>

