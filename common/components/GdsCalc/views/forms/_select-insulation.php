<?php

use yii\web\JsExpression;;

/**
 * @var $this \yii\web\View
 * @var $model \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseInterface
 * @var $form \noIT\core\widgets\ActiveForm
 */

// Вывод картинок "толщины утеплителя"
$urlInsulation = \Yii::$app->urlManager->baseUrl . '/images/calc/insulation/';
$formatInsulation = <<< SCRIPT
function formatInsulation(state) {
    if (!state.id) return state.text; // optgroup
    src = '$urlInsulation' +  state.id.toLowerCase() + '.png'
    return '<img class="flag" src="' + src + '" height="32" style="height: 32px"/>&nbsp;' + state.text;
}

function formatInsulationSelected(state) {
    if (!state.id) return state.text; // optgroup
    src = '$urlInsulation' +  state.id.toLowerCase() + '.png'
    return '<img class="flag" src="' + src + '" height="16" style="height: 16px"/>&nbsp;' + state.text;
}
SCRIPT;
$escapeInsulation = new JsExpression("function(m) { return m; }");
$this->registerJs($formatInsulation, \bryza\components\View::POS_HEAD);

print $form->field($model, 'drain_insulation')->widget('\kartik\widgets\Select2', [
    'data' => $model::listDrainGutterInsulation(),
    'hideSearch' => true,
    'pluginOptions' => [
        'templateResult' => new JsExpression('formatInsulation'),
        'templateSelection' => new JsExpression('formatInsulationSelected'),
        'escapeMarkup' => $escapeInsulation,
        'allowClear' => false,
    ],
]);
