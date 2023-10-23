<?php

/**
 * @var $model \common\models\ProductEntity
 */

use yii\helpers\Html;

?>
<div class="product__top__right _v4">
    <div class="product__top__right__cnt">
        <div class="product__top__right__cnt__inn">
			<div class="product__top__right__cnt__right">

				<?=$this->render('parts/price', ['model' => $model]); ?>

<!--				--><?php //=$this->render('parts/buy', ['model' => $model]); ?>
			</div>

        </div>
        <div class="product__top__right__btxt">
            <span><?= $model->teaser ?></span>
        </div>
    </div>
</div>
