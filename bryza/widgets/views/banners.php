<?php
/** @var $this \yii\web\View */
/** @var $banners \common\models\Banner[] */
?>
<?php if($banners) : ?>
    <section class="mtop">
        <div class="mtop__slider-wrap">
            <div class="mtop__slider">
				<?php foreach ($banners as $banner) : ?>
                    <div class="mtop__slide">
                        <div class="mtop__slide__img objectfit">
                            <img src="<?= $banner->getUrl('original') ?>">
                        </div>
                        <div class="container">
                            <div class="mtop__slide__inn">
                                <div class="mtop__slide__cnt">
                                    <div class="mtop__slide__title"><span> </span></div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endforeach ?>
            </div>
            <div class="mtop__slider-dots"></div>
        </div>
    </section>
<?php endif ?>
