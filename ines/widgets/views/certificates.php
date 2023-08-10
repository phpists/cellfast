<?php

use yii\helpers\Html;


/** @var $this \yii\web\View */
/** @var $items \common\models\Document */
/** @var $item \common\models\Document */

?>
<section class="mcnt__block mdocs">
    <a name="certificates" id="certificates"></a>

    <!-- -->
    <div class="mcnt__block__title"><span><?= Yii::t('app', 'Certificates'); ?></span></div>

    <div class="mserts__cnt">
        <div class="mserts__slider-wrap">

            <a href="#" class="mserts__arr __prev">
                <svg class="svg-icon arrleft-icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#arrleft-icon"></use>
                </svg>
            </a>

            <a href="#" class="mserts__arr __next">
                <svg class="svg-icon arrright-icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#arrright-icon"></use>
                </svg>
            </a>

            <div class="mserts__slider">
				<?php foreach ($items as $item) : ?>
                    <div class="mserts__slide">
                        <div class="mserts__it">
                            <a href="<?= $item->getUploadUrl('file') ?>" class="mserts__it__img" target="_blank">
								<?= Html::img($item->getThumbUploadUrl('cover_image', 'thumb_book')) ?>
                                <svg class="svg-icon search-icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#search-icon"></use>
                                </svg>
                            </a>
                            <div class="mserts__it__name"><a href="<?= $item->getUploadUrl('file') ?>" target="_blank"><?= $item->name ?></a></div>
                            <div class="mserts__it__txt"><span><?= $item->caption ?></span></div>
                        </div>
                    </div>
				<?php endforeach ?>
            </div>

        </div>
    </div>
    <!-- -->

</section>
