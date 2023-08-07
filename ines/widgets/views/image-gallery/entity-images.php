<?php

use noIT\imagecache\helpers\ImagecacheHelper;

/** @var $this \yii\web\View */
/** @var $items \noIT\gallery\models\GalleryImage[] */
/** @var $count integer */
/** @var $params array */
?>
<div class="galleryblock">
    <div class="galleryblock__title"><span>Галерея</span></div>
    <div class="galleryblock__slider-wrap">
        <div class="galleryblock__slider__btns">
            <a href="#" class="galleryblock__slider__arr __prev">
                <svg class="svg-icon arrleft-icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#arrleft-icon"></use>
                </svg>
            </a>
            <a href="#" class="galleryblock__slider__arr __next">
                <svg class="svg-icon arrright-icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#arrright-icon"></use>
                </svg>
            </a>
        </div>
        <div class="galleryblock__slider">
			<?php foreach ( $items as $item ) : ?>
                <div class="galleryblock__slide">
                    <a href="<?= ImagecacheHelper::getImageSrc( $item->url, 'fullsize' ) ?>" class="objectfit">
						<?= ImagecacheHelper::getImage( $item->url, $params['image']['preset'], $params['image']['options'] ) ?>
                        <svg class="svg-icon search-icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#search-icon"></use>
                        </svg>
                    </a>
                </div>
			<?php endforeach ?>
        </div>
    </div>
</div>

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <a class="prev"></a><a class="next"></a><a class="close">×</a>
</div>