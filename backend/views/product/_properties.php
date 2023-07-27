<?php
use common\helpers\AdminHelper;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */

$columns = [
	[
		'name'  => 'id',
		'type'  => MultipleInputColumn::TYPE_HIDDEN_INPUT,
	],
	[
		'name'  => 'product_id',
		'type'  => MultipleInputColumn::TYPE_HIDDEN_INPUT,
	],
];

foreach (Yii::$app->languages->languages as $language) {
	$columns[] = [
		'name'  => AdminHelper::getLangField('name', $language),
		'type'  => MultipleInputColumn::TYPE_TEXT_INPUT,
		'title' => AdminHelper::LangsFieldLabel('Name', $language),
	];
}
foreach (Yii::$app->languages->languages as $language) {
	$columns[] = [
		'name'  => AdminHelper::getLangField('value', $language),
		'type'  => MultipleInputColumn::TYPE_TEXT_INPUT,
		'title' => AdminHelper::LangsFieldLabel('Value', $language),
	];
}

?>
<div class="properties">
	<?= $form->field($model, 'properties')->widget(MultipleInput::className(), [
		'columns' => $columns,
		'allowEmptyList' => true,
		'sortable' => true,
		'enableError' => true,
	])?>
</div>