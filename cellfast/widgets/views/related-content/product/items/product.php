<?php

use noIT\imagecache\helpers\ImagecacheHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
/** @var $model \common\models\ProductEntity */

// TODO $productURL выдает ошибку, не хватает какого-то параметра, проверить - отдалить.
//$productURL = Url::to(["catalog/product", 'product' => $model]);

if (!($model = $model->getModelEntity())) {
    return;
}
?>
<div class="related__content__product">
	<div class="catalog__it">
		<a href="<?= Url::to(["catalog/product", 'product' => $model]) ?>" class="catalog__it__img">
            <?php if($model->image) : ?>
                <?= ImagecacheHelper::getImage($model->image, 'product_list_cover', ['class' => 'product_image']) ?>
            <?php else : ?>
                <?= Html::img('/img/image-placeholder.jpg', ['class' => 'product_image']) ?>
            <?php endif ?>
			<?php /* <div class="catalog__it__img__tags">
                <?php if ($model->definedFeatures) : ?>
                    <div class="catalog__it__img__tags features">
                        <?php foreach ( $model->definedFeatures as $key=>$feature ) : ?>
                            <?= Html::dropDownList('filter['. $model->id .']['. $feature['entity']->slug .']', null, ArrayHelper::map($feature['values'], 'id', 'value_label'), ['title' => $feature['entity']->name]) ?>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
			</div> */ ?>
		</a>
		<div class="catalog__it__name">
            <a href="#">
			    <?= $model->name ?>
            </a>
		</div>
        <?php if (false) :?>
		<div class="catalog__it__lines">
			<?php /* <div class="catalog__it__line"><span><i>Артикул:</i> <?= rand(10,99)?>-<?= rand(100,999)?></span></div> */ ?>
<?php /* <div class="catalog__it__line"><span><i>AEN:</i> <?= rand(100000000,699999999)?></span></div> */?>
		</div>
        <?php if ($model->item && $model->item->price) :?>
		<div class="catalog__it__price"><span><?= $model->item->price ?>&nbsp;  грн</span></div>
        <?php endif ?>

		<div class="catalog__it__btn">
			<a href="javascript:;" data-toggle="modal" data-target="#checkout" class="btn btn_fw btn_blue">Купить</a>
		</div>
        <?php endif ?>
	</div>
</div>
