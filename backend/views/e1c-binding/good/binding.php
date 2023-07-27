<?php
/** @var $this yii\web\View */
/** @var $product \backend\models\Product */
/** @var $definedFeatures \common\models\ProductTypeHasProductFeature */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;

$__params = require __DIR__ .'/__params.php';

$this->title = $__params['items'];
$this->params['breadcrumbs'][] = ['url' => ['good'], 'label' => Yii::t('app', 'Good')];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Binding')];

$columns = [
//    [
//        'class' => \kartik\grid\CheckboxColumn::className(),
//    ],
    [
        'format' => 'raw',
        'value' => function($model, $key, $index, $column) {
            /** @var $model \common\models\soap\E1cGood */
            return Html::input('hidden', "item_id[{$model->id}]", ($model->appEntity ? $model->appEntity->id : ''));
        },
        'label' => false,
    ],
    [
        'attribute' => 'name',
        'format' => 'raw',
        'value' => function($model, $key, $index, $column) {
            return $model->name;
        }
    ],
    [
        'format' => 'raw',
        'value' => function($model, $key, $index, $column) {
        /** @var $model \common\models\soap\E1cGood */
            return Html::input('text', "item_name[{$model->id}]", '', ['class' => 'form-control']);
        },
        'label' => 'Binding product-item',
    ],
];

foreach ($definedFeatures as $feature) {
    $columns[] = [
        'format' => 'raw',
        'value' => function($model, $key, $index, $column) use ($feature) {
            return \dosamigos\selectize\SelectizeDropDownList::widget([
                'name' => "product_feature[{$model->id}][{$feature->product_feature_id}]",
                'value' => isset($model->appEntity->featureGroupedValueIds[$feature->product_feature_id]) ? $model->appEntity->featureGroupedValueIds[$feature->product_feature_id] : null,
                'items' => \yii\helpers\ArrayHelper::map($feature->product_feature->values, 'id', 'value_label'),
                'clientOptions' => [
                    'valueField' => 'id',
                    'labelField' => 'value_label',
                    'searchField' => 'value_label',
                    'create' => new \yii\web\JsExpression('function(input, callback) {
                        $.ajax({
                            url: \'' . \yii\helpers\Url::to(['product-feature/add-value']) . '\',
                            data: {feature_id: ' . $feature->product_feature->id . ', value: input},
                            type: \'post\',
                            dataType: \'json\',
                            success: function(data) {
                                callback(data);
                            }
                       });
                    }'),
                ],
                'options' => [
                    'placeholder' => Yii::t('app', 'Select or add value'),
                    'multiple' => false,
                    'prompt' => '-',
                ],
            ]);
        },
        'label' => $feature->product_feature->native_name,
    ];
}

$gridOptions = [
    'id' => $__params['id'] ."-grid",
    'dataProvider' => $dataProvider,
    'columns' => $columns,

    'responsive' => false,
    'striped' => true,
    'hover' => true,
    'pjax' => false,
    'persistResize' => false,
    'pjaxSettings' => [
        'neverTimeout' => true,
    ],
    'floatHeader' => true,
    'floatHeaderOptions' => [
        'position' => 'auto',
    ],

    'summary' => false,
];

$gridOptions = array_merge($gridOptions, [
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => '<i class="glyphicon glyphicon-book"></i> '. $__params['items'],
        'footer' => '<div class="form-group">'. Html::submitButton(Yii::t('app', 'To bind'), ['class' => 'btn btn-success']) .'</div>',
    ],
    'toolbar'=> [
        ['content'=> ''],
        '{toggleData}',
    ],
]);
?>

<?php $form = ActiveForm::begin([
    'action' => ['binding-good', 'id' => $product->id],
    'method' => 'post',
]); ?>
<?= GridView::widget($gridOptions); ?>
<?php ActiveForm::end(); ?>