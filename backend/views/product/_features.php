<?php
use common\helpers\AdminHelper;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<?php foreach ($model->type->product_features_basic as $feature) :?>
    <div class="product-features-<?= $feature->product_feature_id?>">
        <?= $form->field($model, "featureGroupedValueIds[$feature->product_feature_id]")->widget(\dosamigos\selectize\SelectizeDropDownList::className(), [
            'items' => ArrayHelper::merge(['' => Yii::t('app', 'None')], ArrayHelper::map($feature->product_feature->values, 'id', 'value_label')),
            'clientOptions' => [
                'id' => "product-features-{$feature->product_feature_id}",
                'valueField' => 'id',
                'labelField' => 'value_label',
                'searchField' => 'value_label',
                'create' => new \yii\web\JsExpression('function(input, callback) {
                    $.ajax({
                        url: \''. \yii\helpers\Url::to(['product-feature/add-value']) .'\',
                        data: {feature_id: '. $feature->product_feature->id .', value: input},
                        type: \'post\',
                        dataType: \'json\',
                        success: function(data) {
                            callback(data);
                        }
                   });
                }'),
            ],
            'options' => [
                'placeholder' => Yii::t('app', 'Select or add values (for few langs: {langs})', ['langs' => implode('|', AdminHelper::getLangsField('code'))]),
                'multiple' => $feature->multiple_bool,
            ],
        ])->label($feature->product_feature->native_name)?>
    </div>
<?php endforeach?>
