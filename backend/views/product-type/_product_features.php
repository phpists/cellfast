<?php
use common\helpers\AdminHelper;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductType */
/* @var $form yii\widgets\ActiveForm */

/** TODO - remove to controller */
$features = \backend\models\ProductFeatureSearch::all($model->project_id);

$columns = [
    [
        'name'  => 'product_feature_id',
        'title' => Yii::t('app', 'Product feature'),
        'type'  => \kartik\widgets\Select2::className(),
        'options' => [
            'data' => \yii\helpers\ArrayHelper::map($features, 'id', 'native_name'),
            'language' => Yii::$app->language,
            'options' => [
                'placeholder' => Yii::t('app', 'Select feature'),
                'multiple' => false,
            ],
        ],
    ],
    [
        'name'  => 'defining',
        'title' => Yii::t('app', 'Is defining'),
        'type'  => MultipleInputColumn::TYPE_CHECKBOX,
    ],
    [
        'name'  => 'multiple',
        'title' => Yii::t('app', 'Is multiple'),
        'type'  => MultipleInputColumn::TYPE_CHECKBOX,
    ],
    [
        'name'  => 'in_filter',
        'title' => Yii::t('app', 'Show in filter'),
        'type'  => MultipleInputColumn::TYPE_CHECKBOX,
    ],
    /*[
        'name'  => 'admin_widget',
        'type'  => MultipleInputColumn::TYPE_TEXT_INPUT,
    ],
    [
        'name'  => 'filter_widget',
        'type'  => MultipleInputColumn::TYPE_TEXT_INPUT,
    ],*/
];

?>
<div class="items">
    <?= $form->field($model, 'product_features')->widget(MultipleInput::className(), [
        'columns' => $columns,
        'enableGuessTitle' => true,
        'allowEmptyList' => true,
        'sortable' => true,
        'enableError' => true,
        'addButtonPosition' => MultipleInput::POS_FOOTER
    ])?>
</div>
