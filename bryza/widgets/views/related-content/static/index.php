<?php
/** @var $this \yii\web\View */
/** @var $title string */
/** @var $viewItem string */
/** @var $viewItemParams array */
/** @var $items \yii\db\ActiveRecord[] */

\bryza\widgets\assets\relatedContent\RelatedContentStaticAsset::register($this);

?>
<section class="related__content __static">
    <div class="container">
        <div class="related__title"><span><?= $title; ?></span></div>
        <div class="related__content__body related__content__static">
            <?php
            foreach($items as $item) {
		        echo $this->render($viewItem, [
                    'model' => $item,
                ]);
	        }
	        ?>
        </div>
    </div>
</section>