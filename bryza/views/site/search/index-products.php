<?php

\cellfast\assets\CatalogAsset::register($this);

/**
 * Контейнер вывода списка элементов (используется в статья и новостях как общий шаблон).
 */

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $features \common\models\ProductFeature[] */
/* @var $featureIds int[] */
/* @var $params integer[] */
/* @var $search string */

use yii\helpers\Url;
use yii\web\View;

$this->title = Yii::t('app', 'Catalog');

$__params = require __DIR__ .'/__params.php';

/** TODO - SEO title and meta-tags */
$this->params['breadcrumbs'][] = [
    'label' => $__params['items'],
    'url' => ['search'],
];
if (!empty($params['category'])) {
    foreach($params['category']->parents as $parent) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => Url::to(['catalog/category', 'category' => $parent])];
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
        <?= \cellfast\widgets\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= \cellfast\widgets\Alert::widget() ?>

        <h1 class="catalog__title"><?= ( !empty($params['category']) ? $params['category']->name : $this->title ); ?> "<?= $_GET['search_header']; ?>"</h1>

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
                    <?php if (empty($dataProvider) || !$dataProvider->count) :?>
                        <div class="empty-result">
                            <?= $__params['empty']?>
                        </div>
                    <?php else :?>
                        <div id="<?= $__params['items-wrapper']?>" class="catalog__list <?= $__params['id']?>-items">
                            <?= $this->render('_items', [
                                'dataProvider' => $dataProvider,
                                '__params' => $__params,
//                                'featureIds' => $featureIds,
                            ])?>
                        </div>
                        <div class="clearfix">
                            <?= \cellfast\widgets\PaginationWidget::widget([
                                'pagination' => $dataProvider->pagination,
                                'urlEncode' => false,
                            ]) ?>
                        </div>
                    <?php endif ?>
                </div>
                <!--                <div class="col-lg-3 col-md-4">-->
                <!--                    --><?php //if (!empty($params['category']) && !empty($params['category']->manuals)) :?>
                <!--                        --><?php //= $this->render('_manuals', [
                //                            'category' => $params['category'],
                //                            'to_catalog' => false,
                //                            'to_hidden' => true,
                //                        ])?>
                <!--                    --><?php //endif; ?>
                <!--                    --><?php //= $this->render('_features', [
                //                        'features' => $features,
                //                        'values' => $params['features'],
                //                        'to_hidden' => false,
                //                    ])?>
                <!--                </div>-->
            </div>
        </div>
    </div>
</section>
<?php if (sizeof($articles) > 0) : ?>

    <?= $this->render('articles/index-articles', [
        'articles' => $articles,
        '__params' => $__params,
    ])?>
<?php endif; ?>
