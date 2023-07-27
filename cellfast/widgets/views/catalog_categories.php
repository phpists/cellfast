<?php

use yii\helpers\Url;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $items \common\models\Category[] */
?>
<section class="mcatalog">
    <div class="container">
        <div class="mcatalog__title"><span><?= Yii::t('app', 'Catalog')?></span></div>
        <div class="mcatalog__cnt">
            <?= $this->render('/catalog/_categories', ['items' => $items])?>
            <?php /*
            <div class="mcatalog__col"><a href="<?= Url::to(['catalog/index'])?>" class="mcatalog__it _all">
                    <div class="mcatalog__it__img objectfit"><img src="img/content/mcatalog/1.jpg" alt=""/></div>
                    <div class="mcatalog__it__cnt">
                        <div class="mcatalog__it__cnt__inn">
                            <div class="mcatalog__it__title">
                                <div class="_all_icon"><span></span><span></span><span></span></div><span>весь каталог</span>
                            </div>
                        </div>
                    </div></a></div>
            */ ?>
        </div>
    </div>
</section>