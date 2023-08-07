<?php

/** @var $this \yii\web\View */
/** @var $model \common\models\Product */
/** @var $data array */
/** @var $showHeader boolean */
?>
<div class="product__info__char">
	<?php if ($showHeader) :?>
        <div class="product__info__char__label">
            <span><?= Yii::t('app', 'Label')?></span>
        </div>
	<?php endif?>
	<?php foreach ($data as $row) :?>
        <div class="product__info__char__line">
            <div class="product__info__char__left"><span><?= $row['name']?></span></div>
            <div class="product__info__char__right"><span><?= implode(', ', $row['value_label'])?></span></div>
        </div>
    <?php endforeach?>
</div>