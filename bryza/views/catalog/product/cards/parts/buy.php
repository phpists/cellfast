<?php
use yii\helpers\Html;
?>
<div class="product__top__right__btn">
	<?= Html::input( 'hidden', 'item_id', $itemModel->id, ['class' => '', 'id' => 'product_id'] ) ?>
	<?= Html::input( 'hidden', 'product_id', $itemModel->product->id, ['class' => '', 'id' => 'product_product_id'] ) ?>
	<?php // = Html::button( Yii::t('app', 'To order'), [ 'class' => 'btn btn_fw btn_blue add-to-cart' ])?>
</div>
