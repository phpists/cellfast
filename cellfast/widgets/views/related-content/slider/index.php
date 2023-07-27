<?php
/** @var $this \yii\web\View */
/** @var $title string */
/** @var $viewItem string */
/** @var $viewItemParams array */
/** @var $items \yii\db\ActiveRecord[] */

\cellfast\widgets\assets\relatedContent\RelatedContentSliderAsset::register($this);
?>
<section class="related__content __slider">
    <div class="container">
        <div class="related__title"><span><?= $title; ?></span></div>
        <div class="related__content__body related__content__slider">

            <a href="#" class="slider__arrow __prev">
                <svg class="svg-icon arrleft-icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#arrleft-icon"></use>
                </svg>
            </a>

            <a href="#" class="slider__arrow __next">
                <svg class="svg-icon arrright-icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#arrright-icon"></use>
                </svg>
            </a>

            <div class="slider__wrapper">
	            <?php
	            foreach($items as $item) {
		            echo $this->render($viewItem, [
			            'model' => $item,
		            ]);
	            }
	            ?>
            </div>

        </div>
    </div>
</section>