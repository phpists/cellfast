<?php

\ines\assets\CatalogAsset::register($this);

/* @var $this yii\web\View */
/* @var $category \common\models\Category */
/* @var $features \common\models\ProductFeature[] */
/* @var $featureIds int[] */
/* @var $params integer[] */
/* @var $search string */
/* @var $manualId int */
/* @var $manual string */

use yii\helpers\Url;
use yii\web\View;

$this->title = Yii::t('app', 'Catalog');

$__params = require __DIR__ .'/__params.php';

/** TODO - SEO title and meta-tags */
$this->params['breadcrumbs'][] = [
    'label' => $__params['items'],
    'url' => ['catalog/index'],
];
if (!empty($params['category'])) {
    foreach($params['category']->parents as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => Url::to(['catalog/category', 'category' => $parent])];
    }
    if (isset($params['filter'])) {
        $this->params['breadcrumbs'][] = ['label' => $params['category']->name, 'url' => Url::to(['catalog/category', 'category' => $params['category']])];
        $this->params['breadcrumbs'][] = Yii::$app->productFeature->getFilterLabel($params['filter']);
    } else {
        $this->params['breadcrumbs'][] = $params['category']->name;
    }
} else {
    $this->title = $__params['items'];

    if (!empty($search)) {
        $this->params['breadcrumbs'][] = Yii::t('app', 'Search for {word}', ['word' => $search]);
    }
}
?>

<?php //= $this->render('/content/_slider'); ?>

<section class="catalog catalog-product-index">
    <div class="container">
        <?= \ines\widgets\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= \ines\widgets\Alert::widget() ?>

        <h1 class="catalog__title"><?= $manual['title'] ?></h1>

        <div class="catalog__mbtn catalog__filter__open visible-sm visible-xs">
            <button class="btn btn_fw btn_blue"><?= Yii::t('app', 'Filter')?></button>
        </div>

        <?php if (!empty($params['category']) && !empty($params['category']->manuals)) : ?>
            <div class="catalog__mbtn catalog__manuals__open visible-sm visible-xs">
                <button class="btn btn_fw btn_blue"><?= Yii::t('app', 'Helpful information')?></button>
            </div>
        <?php endif ?>

        <div class="catalog__cnt">
            <div class="row">
                <div class="col-lg-9 col-md-8">
                    <?= $manual['body'] ?>
                </div>
                <div class="col-lg-3 col-md-4">
                    <?php if (!empty($params['category']) && !empty($params['category']->manuals)) :?>
                        <?= $this->render('_manuals', [
                            'category' => $params['category'],
                            'active' => $manualId,
                            'to_catalog' => true,
                            'to_hidden' => false,
                        ])?>
                    <?php endif; ?>
                    <?= $this->render('_features', [
                        'features' => $features,
                        'values' => $params['features'],
                        'to_hidden' => true,
                    ])?>
                </div>
            </div>
        </div>
    </div>
</section>
