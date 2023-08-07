<?php

use yii\helpers\Url;
use yii\helpers\Html;

/*** Контейнер вывода списка каталогов. */

/** @var $this \yii\web\View */
/** @var $tree \common\models\Category[] */

\ines\assets\CatalogAsset::register($this);

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
<section class="catalog-page">
    <div class="container">

	    <?= \ines\widgets\Breadcrumbs::widget([
		    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
	    ]) ?>

        <h1 class="catalog__title"><?= Yii::t('app', 'Catalog')?></h1>
        <div class="mcatalog__row">
            <?= $this->render('_categories', ['items' => $tree])?>
        </div>
    </div>

    <?php /*
    <div class="container">
        <?= \common\components\GdsCalc\widgets\CalcDrainWidget::widget()?>
    </div>
    */ ?>
</section>
