<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \common\models\ProductEntity */
/* @var $featureIds int[] */
/* @var $__params string[] */

$this->title = Yii::t('app', 'Catalog');

$productItem = $model->getItem($featureIds);
if (!is_object($productItem)) {
    $productItem = $model->getDefaultItem();
}

$productURL = Url::to(["catalog/product", 'product' => $model]) . '?item='. $productItem->id;

$coverImageUrl = $productItem->imageUrl ? : $model->image;
?>
<div class="catalog__list__col <?= $__params['id']?>-item">
	<div class="catalog__it" data-features='<?=json_encode($model->definedFeaturesMap)?>'>
		<?php /** Print entity cover-image */ ?>
        <?php if ( $productItem->image ) :?>
            <div class="catalog__it__img">
                <a href="<?= $productURL ?>" class="catalog__it__img__link <?= $__params['id']?>-cover">
                    <img src="<?= $productItem->imageUrl ?>">
                </a>
            </div>
        <?php endif?>

		<?php /** Print entity title */ ?>
		<div class="catalog__it__name <?= $__params['id']?>-title">
			<?= Html::a($model->name, $productURL); ?>
		</div>
		<div class="catalog__it__lines">
			<?php if ($productItem && $productItem->sku) :?>
				<?php /** Print entity SKU */ ?>
				<div class="catalog__it__line <?= $__params['id']?>-sku">
					<span><i><?= Yii::t('app', 'SKU')?>:</i></span><span class="product_sku"> <?= $productItem->sku?></span>
				</div>
			<?php endif?>
			<?php if ($productItem && $productItem->sku) :?>
				<?php /** Print entity AEN */ ?>
                <?php /** TODO Поле пока не поддерживается, временно дублирует SKU */ ?>
				<div class="catalog__it__line <?= $__params['id']?>-sku">
					<span><i><?= Yii::t('app', 'EAN')?>:</i></span><span class="product_aen"> <?= $productItem->sku?></span>
				</div>
			<?php endif?>
            <?php /*
            <div class="catalog__it__line">
                <span>
                    <?= \yii\helpers\StringHelper::truncate($model->body, 100, '...', null, true)?>
                </span>
            </div>
            */ ?>

		</div>
        <?php /*
		<div class="catalog__it__price">
            <?php if ($productItem) :?>
                <?php // Print entity price ?>
                <span class="product_price"><?= $productItem->priceFormated?></span>&nbsp; <?= Yii::t('app', 'UAH')?>
            <?php endif?>
		</div>
		<div class="catalog__it__btn">
			<button type="button" data-productid="<?= $productItem->id; ?>" data-id="<?= $productItem->id; ?>" data-quantity="1" class="add_product_in_cart btn btn_fw btn_blue"><?= Yii::t('app', 'To order')?></button>
		</div>
        */ ?>
	</div>
</div>
