<?php
use yii\helpers\Html;

?><div class="product__top__right _v4">
	<div class="product__top__right__cnt">
		<div class="product__top__right__cnt__inn">
            <?php /*
			<div class="product__top__right__cnt__left">
				<div class="product__top__right__art"><span><?= Yii::t( 'app', 'SKU' ) ?>:&nbsp;</span><span id="product_sku"><?= $model->item->sku ?></span></div>
				<?=$this->render('parts/calculator', ['model' => $model]); ?>
			</div>
            */ ?>
			<div class="product__top__right__cnt__right">

				<?=$this->render('parts/price', ['model' => $model]); ?>
                <?php /*
				<?=$this->render('parts/buy', ['model' => $model]); ?>
                */ ?>
			</div>

		</div>
		<div class="product__top__right__btxt">
            <span><?= $model->teaser ?></span>
        </div>
	</div>
</div>
