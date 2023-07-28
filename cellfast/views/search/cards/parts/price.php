<?php
/**
 * @var $this \yii\web\View
 * @var $model \common\models\ProductItemEntity
 */
?>
<div class="product__top__right__price">
    <?php if ($model->item->commonPrice) :?>
	<div class="product__top__right__price__old">
		<span><storng id="product_comparePrice"><?= $model->item->commonPriceFormated ?></storng>&nbsp;<span id="product_comparePrice_curr">грн</span></span>
	</div>
    <?php endif?>
	<div class="product__top__right__price__normal">
        <span><storng id="product_price"><?= $model->item->priceFormated ?></storng>&nbsp;<span id="product_price_curr">грн</span></span>
	</div>
</div>
