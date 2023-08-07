<?php

bryza\assets\GdsCalcAsset::register($this);

/* @var $this yii\web\View */
/* @var $category \common\models\Category */
/* @var $features \common\models\ProductFeature[] */
/* @var $featureIds int[] */
/* @var $params integer[] */
/* @var $search string */
/* @var $manualId int */
/* @var $manual string */

use common\components\GdsCalc\widgets\CalcDrainWidget;use yii\helpers\Url;
use yii\web\View;

$this->title = $this->title ? : Yii::t('app', 'Калькулятор водостоков');

$__params = require __DIR__ .'/__params.php';

/** TODO - SEO title and meta-tags */
$this->params['breadcrumbs'][] = [
    'label' => $__params['items'],
    'url' => ['catalog/index'],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Водосточные системы',
    'url' => Url::to('/catalog/vodostochnye-sistemy-bryza'),
];
$this->params['breadcrumbs'][] = [
    'label' => 'Калькулятор водосточных систем',
];
/*if (!empty($params['category'])) {
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
}*/
?>

<?php //= $this->render('/content/_slider'); ?>



<section class="catalog catalog-product-index">
    <div class="container">
        <?= \bryza\widgets\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= \bryza\widgets\Alert::widget() ?>

        <h1 class="catalog__title"><?= $this->title ?></h1>

        <div class="">
            <?= CalcDrainWidget::widget(['assets' => 'bryza\assets\GdsCalcAsset',]) ?>
        </div>
    </div>
</section>
