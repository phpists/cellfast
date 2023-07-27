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
		<?= AdminHelper::getProjectWidget($form, $model)?>

		<?= $form->field($model, 'native_name')->textInput(['maxlength' => true]) ?>

		<?php foreach (Yii::$app->languages->languages as $language) :?>
			<?= $form->field($model, AdminHelper::getLangField('name', $language))->textInput(['maxlength' => true]) ?>
		<?php endforeach?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $this->render('_product_features', [
			'model' => $model,
			'form' => $form,
		])?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= AdminHelper::getSelectWidget($form, $model, 'rel_packages', ArrayHelper::map(\common\models\Package::find()->all(), 'id', 'native_name'), ['multiple' => true])?>
    </div>
    <div class="col like-box">
		<?= AdminHelper::getStatusWidget($form, $model)?>
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

