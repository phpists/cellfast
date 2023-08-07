<?php
use yii\helpers\Html;

// TODO передать параметры для калькулятора в json формате.
$itemParam = '[{"count": 20,"name": "Паллет"},{"count": 10,"name": "Упаковка"}]';
?><div class="product__top__right__countline">
	<div class="product__top__right__counter">
		<a href="javascript:;" class="_plus"></a>
		<a href="javascript:;" class="_minus"></a>
		<?= Html::input( 'number', 'quantity', 1, ['class' => '', 'id' => 'product_counter'] ) ?>
	</div>
	<div class="calculator__dropdown" data-params='<?= $itemParam; ?>'>
		<?= Html::button( '<svg class="svg-icon calc-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#calc-icon"></use></svg>', [
			'class' => 'dropdown-toggle product__top__right__calc',
			'data-target' => '#product_counter',
		] )?>
	</div>
</div>