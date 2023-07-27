<?php
use common\helpers\AdminHelper;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductFeature */
/* @var $form yii\widgets\ActiveForm */

switch ($model->filter_widget) {
    case 'color':
	    $valueType = \kartik\widgets\ColorInput::className();
        break;
    default:
	    $valueType = MultipleInputColumn::TYPE_TEXT_INPUT;
	    break;
}


$columns = [
    [
        'name'  => 'id',
        'type'  => MultipleInputColumn::TYPE_HIDDEN_INPUT,
    ],
    [
        'name'  => 'value',
        'type'  => $valueType,
        'title' => Yii::t('app', 'Value'),
        'value' => function($model) {
            return is_object($model) ? $model->value : null;
        }
    ],
];

foreach (Yii::$app->languages->languages as $language) {
    $columns[] = [
        'name'  => AdminHelper::getLangField('value_label', $language),
        'type'  => MultipleInputColumn::TYPE_TEXT_INPUT,
        'title' => AdminHelper::LangsFieldLabel('Value label', $language),
    ];
}

?>
<div class="values">
    <?= $form->field($model, 'values')->widget(MultipleInput::className(), [
        'columns' => $columns,
        'allowEmptyList' => true,
        'sortable' => true,
        'enableError' => true,
    ])?>
</div>
