<?php
/** @var $this \yii\web\View */
/** @var $cart \common\models\Cart */


?>
<div class="checkout__block">
    <div class="checkout__block__head">
        <div class="checkout__block__header"><?= $this->title; ?></div>
    </div>
    <div class="checkout__block__table">
        <div class="scroll">
            <div class="checkout__block__table_items">
	            <?php if( count($cart->items) > 0 ){ ?>
                    <?php foreach ($cart->items as $item) { ?>
                        <?php echo $this->render('items/product', [
                            'model' => $item
                        ]);?>
                    <?php } ?>
	            <?php } else { ?>
		            <?php echo $this->render('items/empty');?>
	            <?php } ?>
            </div>
        </div>
    </div>
    <div class="checkout__block__footer">
        <div class="checkout__block__info">
            <div class="checkout__block__info__text col-30">
                <a href="javascript:;" data-dismiss="modal" aria-label="Close" class="btn btn_gray"><?= Yii::t('app', 'Продолжить покупки'); ?></a>
            </div>
            <div class="checkout__block__info__text col-40 align-right">
                <strong><?= Yii::t('app', 'Итого:'); ?><span><?php echo $cart->summ; ?> <?php // echo $cart->summ_label; ?> </span></strong>
            </div>
            <div class="checkout__block__info__button col-30">
                <a href="javascript:;" data-toggle="modal" data-target="#ordering" class="btn btn_blue"><?= Yii::t('app', 'Оформить заказ'); ?></a>
            </div>
        </div>
    </div>
</div>
