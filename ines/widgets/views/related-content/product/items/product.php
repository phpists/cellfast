<?php
use yii\helpers\Url;
use yii\helpers\Html;
/** @var $model \yii\db\ActiveRecord */

// TODO $productURL выдает ошибку, не хватает какого-то параметра, проверить - отдалить.
//$productURL = Url::to(["catalog/product", 'product' => $model]);

?>
<div class="related__content__product">
	<div class="catalog__it">
		<a href="#" class="catalog__it__img">
			<img src="/img/content/catalog/1.jpg" alt=""/>
			<div class="catalog__it__img__tags">
				<span><?= rand(100,999) ?> мм</span>
				<span><?= rand(10,99) ?> м</span>
			</div>
		</a>
		<div class="catalog__it__name">
            <a href="#">
			    <?= $model->name ?>
            </a>
		</div>
		<div class="catalog__it__lines">
			<div class="catalog__it__line"><span><i>Артикул:</i> <?= rand(10,99)?>-<?= rand(100,999) ?></span></div>
			<div class="catalog__it__line"><span><i>AEN:</i> <?= rand(100000000,699999999) ?></span></div>
		</div>
		<div class="catalog__it__price"><span><?= rand(100,999) ?> грн</span></div>
		<div class="catalog__it__btn">
			<a href="javascript:;" data-toggle="modal" data-target="#checkout" class="btn btn_fw btn_blue">Купить</a>
		</div>
	</div>
</div>