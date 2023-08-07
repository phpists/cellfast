<?php

/* @var $this yii\web\View */
/* @var $to_catalog bool */
/* @var $to_calculator bool */
/* @var $to_hidden bool */
/* @var $category \common\models\Category */
/* @var $active int */
?>

<div class="catalog__filter__wrap catalog__filter__manuals__wrap<?= !empty($to_hidden) ? ' catalog__filter__wrap__hidden' : '' ?> js_catalog__filter__block ">
    <div class="catalog__filter__close visible-sm visible-xs"><a href="#"><?= Yii::t('app', 'Close') ?></a></div>
    <div class="catalog__filter__title"><span><?= Yii::t('app', 'Helpful information') ?></span></div>
    <div class="catalog__filter">
        <div class="ctlg__fltr__bl">
        <?php if ($to_catalog) :?>
            <div class="filter-option"><?= \noIT\core\helpers\Html::a(Yii::t('app', 'Вернуться в каталог →'), ['catalog/category', 'category' => $category])?></div>
        <?php endif?>
        <?php if (!empty($to_calculator)) :?>
            <div class="filter-option"><?= \noIT\core\helpers\Html::a(Yii::t('app', 'Расчет водостоков →'), ['catalog/gds-calc'])?></div>
        <?php endif?>
        <?php foreach ($category->manuals as $i => $manual) : ?>
            <?php if (isset($active) && $i == $active) :?>
                <div class="filter-option"><?= "• " . $manual['title'] ?></div>
            <?php else :?>
                <div class="filter-option"><?= \noIT\core\helpers\Html::a($manual['title'], ['catalog/category', 'category' => $category, 'manual' => $i])?></div>
            <?php endif?>
        <?php endforeach ?>
        </div>
    </div>
</div>
<br>
