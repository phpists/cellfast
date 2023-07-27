<?php

use yii\helpers\Url;
use yii\helpers\Html;

/*** Контейнер вывода списка каталогов. */

/** @var $this \yii\web\View */
/** @var $tree \common\models\Category[] */

\cellfast\assets\CatalogAsset::register($this);

$this->title = Yii::t('app', 'Catalog');

$__params = require __DIR__ .'/__params.php';

/** TODO - SEO title and meta-tags */
if (isset($category)) {
	$this->params['breadcrumbs'][] = [
		'label' => $__params['items'],
		'url' => ['catalog/index'],
	];
	foreach($category->parents as $parent) {
		$this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => Url::to(['catalog/category', 'category' => $parent])];
	}
	$this->params['breadcrumbs'][] = $category->name;
	$this->title = $category->name;
} else {
	$this->title = $__params['items'];
	$this->params['breadcrumbs'][] = Yii::t('app', 'Catalog');
}
?>

<div class="container">
    <?= \cellfast\widgets\Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
</div>

<section class="catalog">
    <div class="container">
        <h1 class="catalog__title"><?= Yii::t('app', 'Catalog') ?></h1>
        <div class="mcatalog__cnt">
            <?= $this->render('_categories', ['items' => $tree]) ?>
        </div>
    </div>
</section>