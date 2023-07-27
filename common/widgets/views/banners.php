<?php
/** @var $this \yii\web\View */
/** @var $items \common\models\Banner[] */
?>
<section class="mtop">
    <div class="mtop__slider-wrap">
        <div class="mtop__slider">
            <?php foreach ($items as $banner) :?>
            <div class="mtop__slide">
                <div class="mtop__slide__img objectfit"><img src="<?= $banner->src?>" alt="<?= $banner->title?>"/></div>
                <div class="container">
                    <div class="mtop__slide__inn">
                        <div class="mtop__slide__cnt">
                            <div class="mtop__slide__title"><span><?= $banner->title?></span></div>
                            <?php if ($banner->caption) :?>
                            <div class="mtop__slide__txt"><span><?= $banner->caption?></span></div>
                            <?php endif?>
                            <?php if ($banner->link) :?>
                            <div class="mtop__slide__btn"><a href="<?= $banner->link?>" class="btn btn_fw btn_white"><?= $banner->button?></a></div>
                            <?php endif?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach?>
        </div>
        <div class="mtop__slider-dots"></div>
    </div>
</section>
