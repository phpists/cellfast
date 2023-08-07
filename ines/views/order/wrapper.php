<?php
/** @var $cart \common\models\Cart */
?><section class="checkout__wrapper">
	<div class="container">
		<?= $this->render('cart', [
			'cart' => $cart,
		]); ?>
	</div>
</section>