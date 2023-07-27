<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\AdminHelper;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductItem */
/* @var $product_type \common\models\ProductType */
/* @var $form yii\widgets\ActiveForm */
$__params = require __DIR__ .'/__params.php';
?>

<div class="<?= $__params['id']?>-item-form">

	<?= Html::activeHiddenInput($model, 'product_id')?>

    <div class="row">
        <div class="col-md-8">
            <div class="bs-callout bs-callout-base">
				<?= $form->field($model, 'native_name')->textInput(['maxlength' => true]) ?>

				<?php foreach (Yii::$app->languages->languages as $language) :?>
					<?= $form->field($model, AdminHelper::getLangField('name', $language))->textInput(['maxlength' => true]) ?>
				<?php endforeach?>

				<?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bs-callout bs-callout-seo">
				<?php foreach ($product_type->product_features_define as $feature) : ?>
                    <div class="product-define-features-<?= $feature->product_feature_id ?>">
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
								'placeholder' => Yii::t('app', 'Select or add defining value (for few langs: {langs})', ['langs' => implode('|', AdminHelper::getLangsField('code'))]),
								'multiple' => $feature->multiple_bool,
							],
						])->label($feature->product_feature->native_name) ?>
                    </div>
				<?php endforeach ?>
            </div>
            <div class="bs-callout bs-callout-media">
				<?= AdminHelper::getImageUploadWidget($form, $model) ?>
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="bs-callout bs-callout-options">
                <label><?= Yii::t('app', 'Prices') ?></label>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td><?= Yii::t('app', 'Price type') ?></td>
                        <td><?= Yii::t('app', 'Price') ?></td>
                        <td><?= Yii::t('app', 'Common price') ?></td>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( \common\models\PriceType::find()->all() as $price_type ) : ?>
                        <tr>
                            <td><?= $price_type->native_name?></td>
                            <td><?= Html::activeInput('number', $model, "prices[{$price_type->id}][price]", ['step' => 0.01, 'class' => 'form-control']) ?></td>
                            <td><?= Html::activeInput('number', $model, "prices[{$price_type->id}][common_price]", ['step' => 0.01, 'class' => 'form-control']) ?></td>
                        </tr>
					<?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="bs-callout bs-callout-options">
                <label><?= Yii::t('app', 'Warehouses') ?></label>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td><?= Yii::t('app', 'Warehouse') ?></td>
                        <td><?= Yii::t('app', 'Availability') ?></td>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( \common\models\Warehouse::find()->all() as $warehouse ) : ?>
                        <tr>
                            <td><?= $warehouse->native_name ?></td>
                            <td><?= Html::activeDropDownList($model, "availabilities[{$warehouse->id}][status]", \common\models\ProductAvailability::statusLabels(), ['class' => 'form-control']) ?></td>
                        </tr>
					<?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="bs-callout bs-callout-options">
                <label><?= Yii::t('app', 'Quantities of packages') ?></label>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td><?= Yii::t('app', 'Package') ?></td>
                        <td><?= Yii::t('app', 'Quantity') ?></td>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( \common\models\Package::find()->all() as $package) :?>
                        <tr>
                            <td><?= $package->native_name?></td>
                            <td><?= Html::activeInput('number', $model, "packages[{$package->id}][quantity]", ['step' => 0.01, 'class' => 'form-control']) ?></td>
                        </tr>
					<?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
