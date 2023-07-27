<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\AdminHelper;
use noIT\feature\widgets\FeatureWidgetHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductFeature */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'native_name')->textInput(['maxlength' => true]) ?>

		<?php foreach (Yii::$app->languages->languages as $language) :?>
			<?= $form->field($model, AdminHelper::getLangField('name', $language))->textInput(['maxlength' => true]) ?>
		<?php endforeach?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= AdminHelper::getBooleanWidget($form, $model, 'includeVAT', 1)?>
    </div>
    <div class="col like-box">
	    <?= $form->field($model, 'sort_order')->input('number', ['step' => 1]) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col">
        <div class="form-group">
            <br>
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
