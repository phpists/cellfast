<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use noIT\imagecache\helpers\ImagecacheHelper;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model \common\models\ProductEntity */
/* @var $featureIds int[] */
/* @var $__params string[] */

$this->title = Yii::t('app', 'Catalog');

$productURL = Url::to(["catalog/product", 'product' => $model, 'filter' => json_encode($featureIds)]);

$productItem = $model->item ? : $model->getItem($featureIds);
if (!is_object($productItem)) {
    $productItem = $model->getDefaultItem();
}

// todo Hide prices
$price = false; // $productItem ? $productItem->priceFormated : '-ß';
$commonPrice = false; // $productItem ? $productItem->commonPriceFormated : '-';
$ucgfea = $productItem ? $productItem->ucgfea : '-';
$sku = $productItem ? $productItem->sku : '-';
?>
<div class="catalog__list__col <?= $__params['id'] ?>-item">
    <div class="catalog__it" data-features='<?= json_encode($model->definedFeaturesMap) ?>'>

        <div class="catalog__it__img">
            <a href="<?= $productURL ?>" class="catalog__it__img__link <?= $__params['id'] ?>-cover">
				<?php if($model->image) : ?>
					<?= ImagecacheHelper::getImage($model->image, 'product_list_cover', ['class' => 'product_image']) ?>
				<?php else : ?>
					<?= Html::img('/img/image-placeholder.jpg', ['class' => 'product_image']) ?>
				<?php endif ?>
            </a>
			<?php if ($model->definedFeatures) : ?>
                <div class="catalog__it__img__tags features">
					<?php foreach ( $model->definedFeatures as $key=>$feature ) : ?>
						<?= Html::dropDownList('filter['. $model->id .']['. $feature['entity']->slug .']', $featureIds, ArrayHelper::map($feature['values'], 'id', 'value_label'), ['title' => $feature['entity']->name]) ?>
					<?php endforeach ?>
                </div>
			<?php endif ?>
        </div>

		<?php /** Print entity title */ ?>
        <div class="catalog__it__name <?= $__params['id'] ?>-title">
			<?= Html::a($model->name, $productURL); ?>
        </div>
		<?php if ($sku) : ?>
            <div class="catalog__it__lines">
                <div class="catalog__it__line <?= $__params['id'] ?>-sku">
                    <span><i><?= Yii::t('app', 'SKU') ?>: </i></span><span class="product_sku"><?= $sku ?></span>
                </div>
				<?php /** Print entity UCG FEA */ ?>
				<?php /** TODO Позже надо будет рассмотреть возможность выгрузки EAN */ ?>
				<?php /*
            <div class="catalog__it__line <?= $__params['id']?>-ucgfea">
                <span><i><?= Yii::t('app', 'UCG&nbsp;FEA')?>:</i></span><span class="product_ucgfea"> <?= $ucgfea?></span>
            </div>
            */ ?>
                <div class="catalog__it__line">
                    <span></span>
					<?php /*
                <span>
                    <?= \yii\helpers\StringHelper::truncate($model->body, 70, '...', null, true)?>
                </span>
                */ ?>
                </div>

            </div>
		<?php endif ?>

		<?php if(false) : ?>
            <div class="catalog__it__price">
                <span><strong class="product_price"><?= $price ?></strong>&nbsp;<strong class="product_price_curr"><?= Yii::t('app', 'UAH') ?></strong></span>
                <span class="product_commonPrice"><?= $commonPrice ?>&nbsp;<?= Yii::t('app', 'UAH') ?></span>
            </div>
		<?php endif ?>

		<?php if(false) : ?>
            <div class="catalog__it__btn">
				<?php /* <button type="button" data-productid="<?= $productItem->id; ?>" data-id="<?= $productItem->id; ?>" data-quantity="1" class="add_product_in_cart btn btn_fw btn_blue"><?= Yii::t('app', 'To order')?></button> */ ?>
            </div>
		<?php endif ?>

    </div>
</div>
