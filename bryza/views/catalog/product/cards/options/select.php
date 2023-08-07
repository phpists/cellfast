<?php

/** @var $this \yii\web\View */
/** @var $model \common\models\ProductEntity */
/** @var $itemModel \common\models\ProductItemEntity */
/** @var $feature \common\models\ProductFeatureEntity */
/** @var $values \common\models\ProductFeatureValueEntity[] */
?>
<div class="product__top__right__checkline">
	<div class="product__top__right__checkline__title"><span><?php echo $feature->name; ?>:</span></div>
	<div class="product__top__right__checks">
		<div class="product__top__right__select">
			<select class="take_to_ajax selectpicker" name="<?php echo $feature->slug; ?>">
				<?php foreach( array_reverse($values) as $key => $option ){ ?>
					<option <?= (in_array($option->id, $itemModel->featureValueIds) ? 'selected="selected"' : ''); ?> value="<?php echo $option->id; ?>"><?php echo $option->value_label; ?></option>
				<?php }?>
			</select>
		</div>
	</div>
</div>
