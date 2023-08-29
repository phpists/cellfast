<?php
use yii\helpers\Html;

?><div class="product__top__right _v1">
	<div class="product__top__right__cnt">
		<div class="product__top__right__cnt__inn">
			<div class="product__top__right__cnt__left">
                <div class="product__top__right__har-title"><span><?= Yii::t( 'app', 'Choice of features' ) ?></span></div>
                <div class="product__top__right__checklines">
	                <?php foreach ( $features as $feature ) :?>
		                <?= $this->render('infocard_option', [
			                'model' => $model,
                            'itemModel' => $itemModel,
			                'feature' => $feature,
		                ])?>
	                <?php endforeach?>
                </div>
			</div>
			<div class="product__top__right__cnt__right">
                <div class="product__top__right__har-list"><a href="#" data-toggle="modal" data-target="#select_list_items"><?= Yii::t( 'app', 'Selecting from the list' ) ?></a></div>
                <div class="product__top__right__art"><span><?= Yii::t( 'app', 'SKU' ) ?>:&nbsp;</span><span id="product_sku"><?= $itemModel->sku ?></span></div>

				<?=$this->render('parts/price', ['itemModel' => $itemModel,]); ?>

				<?php // =$this->render('parts/calculator', ['model' => $model]); ?>

				<?=$this->render('parts/buy', ['itemModel' => $itemModel,]); ?>
			</div>
		</div>
        <div class="product__top__right__btxt">
            <span><?= $model->teaser ?></span>
        </div>
	</div>
</div>
