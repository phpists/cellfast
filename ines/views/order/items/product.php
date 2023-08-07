<?php
use yii\helpers\Html;
use yii\helpers\Url;
/** @var $model \common\models\CartItem */

//echo '<pre style="color: darkblue;padding:5px;border:1px solid gray; background-color:lightgray;">';
/**
 * TODO передать параметры для калькулятора в json формате.
 * дуль задачи с app/ines/views/catalog/product/cards/parts/calculator.php:4
 **/
$itemParam = '[{"count": 20,"name": "Паллет"},{"count": 10,"name": "Упаковка"}]';
?>
<div class="checkout__block__table__items__body">
	<div class="checkout__block__table__items__body__item">
		<div class="item__image">
			<?php if ( isset($model->productItem->image) ) { ?>
				<?= \noIT\imagecache\helpers\ImagecacheHelper::getImage($model->productItem->image, 'entity_image_gallery', ['class' => ''])?>
			<?php } ?>
		</div>
		<div class="item__name align-left">
            <?=$model->productItem->name; ?>
        </div>
		<div class="item__amount align-center">
			<div class="checkout__block__table__counter">
				<input type="number" value="<?=$model->quantity; ?>" id="<?= $model->productItem->id ?>"/>
				<a href="javascript:;" class="_plus" data-productid="<?= $model->productItem->id; ?>"></a>
				<a href="javascript:;" class="_minus" data-productid="<?= $model->productItem->id; ?>"></a>
			</div>
		</div>

		<div class="item__button calculator__dropdown" data-params='<?= $itemParam; ?>'>
			<a href="javascript:;" data-target="#<?= $model->productItem->id ?>" class="dropdown-toggle checkout__block__table__calc">
				<svg class="svg-icon calc-icon">
					<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#calc-icon"></use>
				</svg>
			</a>
		</div>

		<div class="item__price align-center">
			<span class="item__price__value"><?=$model->productItem->price; ?></span>
            <span class="item__price__currency"> <?= Yii::t('app', 'грн.'); ?></span>
		</div>
		<div class="item__price align-center">
			<span class="item__price__value"><?=$model->productItem->price * $model->quantity; ?></span>
            <span class="item__price__currency"> <?= Yii::t('app', 'грн.'); ?></span>
		</div>

		<div class="item__trash align-center">
			<a href="javascript:;" data-productid="<?= $model->productItem->id; ?>" class="trash-product">
				<svg class="svg-icon trash-icon">
					<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#trash-icon"></use>
				</svg>
			</a>
		</div>
	</div>
</div>
