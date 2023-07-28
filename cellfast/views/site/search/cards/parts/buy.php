<?php
use yii\helpers\Html;
?>
<div class="product__top__right__btn">
	<?= Html::input( 'hidden', 'item_id', $model->item->id, ['class' => '', 'id' => 'product_id'] ) ?>
	<?= Html::input( 'hidden', 'product_id', $model->id, ['class' => '', 'id' => 'product_product_id'] ) ?>
	<?= Html::button( Yii::t('app', 'To order'), [ 'class' => 'btn btn_fw btn_blue add-to-cart' ])?>
</div>