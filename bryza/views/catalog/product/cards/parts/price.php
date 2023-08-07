<?php
/**
 * @var $this \yii\web\View
 * @var $itemModel \common\models\ProductItemEntity
 */
?>
<div class="product__top__right__price">
    <?php if ($itemModel->commonPrice) :?>
        <div class="product__top__right__price__old">
            <span><storng id="product_comparePrice"><?= $itemModel->commonPriceFormated ?></storng>&nbsp;<span id="product_comparePrice_curr">грн</span></span>
        </div>
    <?php endif?>
    <div class="product__top__right__price__normal">
        <span><storng id="product_price"><?= $itemModel->priceFormated ?></storng>&nbsp;<span id="product_price_curr">грн</span></span>
    </div>
</div>
