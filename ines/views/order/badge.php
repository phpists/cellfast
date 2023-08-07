<?php
/** @var $this \yii\web\View */
/** @var $cart \common\models\Cart */
?>
<div class="header__card__icon">
	<a href="javascript:;">
		<svg class="svg-icon card-icon">
			<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#card-icon"></use>
		</svg>
		<div class="header__card__icon__num" id="cart-modal-badge"><?= isset($cart->quantity) ? $cart->quantity : 0; ?></div>
	</a>
</div>
<div class="header__card__cnt">
	<div class="header__card__title">
		<span><?= isset($cart->quantity) && $cart->quantity > 0 ? "Корзина" : 'Корзина пуста' ?></span>
	</div>
	<div class="header__card__txt">
		<span><?= isset($cart->quantity) ? $cart->quantity : 0; ?> товара, <?= isset($cart->summ) ? $cart->summ : 0; ?></span>
	</div>
</div>