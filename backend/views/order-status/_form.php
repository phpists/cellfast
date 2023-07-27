<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\AdminHelper;
use noIT\feature\widgets\FeatureWidgetHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderStatus */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'native_name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'e1c_slug')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'sort_order')->input('number', ['step' => 1]) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= AdminHelper::getBooleanWidget($form, $model, 'cancel') ?>
    </div>

    <div class="col like-box">
		<?= AdminHelper::getBooleanWidget($form, $model, 'accept') ?>
    </div>

    <div class="col like-box">
		<?= AdminHelper::getBooleanWidget($form, $model, 'success') ?>
		<?php // TODO - Add sort widget ?>
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
