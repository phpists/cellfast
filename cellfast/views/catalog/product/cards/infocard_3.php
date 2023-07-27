<?php

/**
 * @var $this \yii\web\View
 * @var $model \common\models\ProductEntity
 * @var $features array
 * @var $featureIds int[]|null
 */

?><div class="product__top__right _v1">
	<div class="product__top__right__cnt">
		<div class="product__top__right__cnt__inn">
			<div class="product__top__right__cnt__left">
<!--                <div class="product__top__right__har-title"><span>--><?//= Yii::t( 'app', 'Choice of features' ) ?><!--</span></div>-->
                <div class="product__top__right__checklines">
	                <?php foreach ( $features as $feature ) :?>
		                <?= $this->render('infocard_option', [
			                'model' => $model,
			                'feature' => $feature,
                            'featureIds' => $featureIds
		                ])?>
	                <?php endforeach?>
                </div>
			</div>
			<div class="product__top__right__cnt__right">
<!--                <div class="product__top__right__har-list"><a href="#" data-toggle="modal" data-target="#select_list_items">--><?//= Yii::t( 'app', 'Selecting from the list' ) ?><!--</a></div>-->

                <div class="product__top__right__art"><span><?= Yii::t( 'app', 'SKU' ) ?>:&nbsp;</span><span id="product_sku"><?= $model->item ? $model->item->sku : '' ?></span></div>

                <?php if ( ($packages = $model->item->packages)) :?>
                    <?php if ( !empty($packages['packet']) ) :?>
                        <div class="product__top__right__art"><span><?= Yii::t( 'app', 'Количество в упаковке' ) ?>:&nbsp;</span><span id="product_packages_packet"><?= $packages['packet'] ?></span>&nbsp;шт.</div>
                    <?php endif ?>
                    <?php if ( !empty($packages['packet']) ) :?>
                        <div class="product__top__right__art"><span><?= Yii::t( 'app', 'Количество в паллете' ) ?>:&nbsp;</span><span id="product_packages_pallet"><?= $packages['pallet'] ?></span>&nbsp;шт.</div>
                    <?php endif ?>
                <?php endif ?>
            <?php // todo Hide prices ?>
            <?php // =$this->render('parts/price', ['model' => $model]); ?>

				<?=$this->render('parts/calculator', ['model' => $model]); ?>

				<?php //=$this->render('parts/buy', ['model' => $model]); ?>
			</div>
		</div>
	</div>
</div>
