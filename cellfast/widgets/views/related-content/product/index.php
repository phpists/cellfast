<?php
/** @var $this \yii\web\View */
/** @var $title string */
/** @var $viewItem string */
/** @var $viewItemParams array */
/** @var $items \yii\db\ActiveRecord[] */

\cellfast\widgets\assets\relatedContent\RelatedContentProductAsset::register($this);

?>
<section class="related__content product__list">
    <div class="container">
        <div class="related__title"><span><?= $title; ?></span></div>
        <div class="related__content__body">
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