<?php
/** @var $this \yii\web\View */
/** @var $model \common\models\ProductEntity */
/** @var $feature array */
/** @var $featureIds int[]|null */

if (count($feature['values']) > 2) { // Select
	echo $this->render( 'options/select', [
		'product' => $model,
		'feature' => $feature['entity'],
		'values' => $feature['values'],
        'featureIds' => $featureIds
	] );
} else { // Radio
	echo $this->render( 'options/radio', [
		'product' => $model,
		'feature' => $feature['entity'],
		'values' => $feature['values'],
        'featureIds' => $featureIds
	] );
}
