<?php

/* @var $this yii\web\View */
/* @var $to_hidden bool */
/* @var $features \common\models\ProductFeature[] */
/* @var $values array */
?>

<div class="catalog__filter__wrap catalog__filter__filters__wrap<?= !empty($to_hidden) ? ' catalog__filter__wrap__hidden' : '' ?> js_catalog__filter__block">
    <div class="catalog__filter__close visible-sm visible-xs"><a href="#"><?= Yii::t('app', 'Close') ?></a></div>
    <div class="catalog__filter__title"><span><?= Yii::t('app', 'Filters') ?></span></div>
    <div class="catalog__filter">
        <?php foreach ($features as $feature) : ?>
            <div class="ctlg__fltr__bl">
                <?= $this->render("feature-widgets/{$feature['group']->filter_widget}", [
                    'model' => $feature,
                    'values' => isset($values[$feature['group']->id]) ? $values[$feature['group']->id] : [],
                ])?>
            </div>
        <?php endforeach ?>
    </div>
</div>
