<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col like-box">
		<?= $form->field($model, 'status')->widget(\backend\widgets\MetronicSingleCheckbox::className(), [
			'value' => \backend\models\Category::STATUS_ACTIVE
		]) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="form-group">
	<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
