<?php

use yii\web\JsExpression;;

/**
 * @var $this \yii\web\View
 * @var $model \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseInterface
 * @var $form \noIT\core\widgets\ActiveForm
 */

// Вывод картинок "типа монтажа"
$urlInstallType = \Yii::$app->urlManager->baseUrl . '/images/calc/install-type/';
$formatInstallType = <<< SCRIPT
function formatInstallType(state) {
    if (!state.id) return state.text; // optgroup
    src = '$urlInstallType' +  state.id.toLowerCase() + '.png'
    return '<img class="flag" src="' + src + '" height="32" style="height: 32px"/>&nbsp;' + state.text;
}
function formatInstallTypeSelected(state) {
    if (!state.id) return state.text; // optgroup
    src = '$urlInstallType' +  state.id.toLowerCase() + '.png'
    return '<img class="flag" src="' + src + '" height="16" style="height: 16px"/>&nbsp;' + state.text;
}
SCRIPT;
$escapeInstallType = new JsExpression("function(m) { return m; }");
$this->registerJs($formatInstallType, \bryza\components\View::POS_HEAD);

print $form->field($model, 'drain_gutter_install_type')->widget('\kartik\widgets\Select2', [
    'data' => $model::listDrainGutterInstallType(),
    'hideSearch' => true,
    'pluginOptions' => [
        'templateResult' => new JsExpression('formatInstallType'),
        'templateSelection' => new JsExpression('formatInstallTypeSelected'),
        'escapeMarkup' => $escapeInstallType,
        'allowClear' => false,
    ],
]);
