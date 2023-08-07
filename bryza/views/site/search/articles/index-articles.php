<?php

\cellfast\assets\ContentsAsset::register($this);

/**
 * Контейнер вывода списка элементов (используется в статья и новостях как общий шаблон).
 */

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
?>
<section class="content <?= $__params['id'] ?>-index">
    <div class="container">

        <?= \cellfast\widgets\Alert::widget() ?>

        <div class="content__title"><span><?= Yii::t('app', 'Articles') ?></span></div>

        <div id="<?= $__params['items-wrapper'] ?>" class="content__body <?= $__params['id'] ?>-items">
            <?= $this->render('_items', [
                'articles' => $articles,
                '__params' => $__params,
            ])?>
        </div>
    </div>
</section>