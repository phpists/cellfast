<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ProductEntity */
/** @var $itemModel, \common\models\ProductItemEntity */
/** @var $feature array */

if (count($feature['values']) > 2) { // Select
	echo $this->render( 'options/select', [
		'product' => $model,
		'itemModel' => $itemModel,
		'feature' => $feature['entity'],
		'values' => $feature['values'],
	] );
} else { // Radio
	echo $this->render( 'options/radio', [
		'product' => $model,
        'itemModel' => $itemModel,
		'feature' => $feature['entity'],
		'values' => $feature['values'],
	] );
}
