<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductTypeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'native_name') ?>

	<?= $form->field($model, 'cancel')->widget(\backend\widgets\MetronicSingleCheckbox::className(), [
		'value' => 1
	]) ?>

	<?= $form->field($model, 'accept')->widget(\backend\widgets\MetronicSingleCheckbox::className(), [
		'value' => 1
	]) ?>

	<?= $form->field($model, 'success')->widget(\backend\widgets\MetronicSingleCheckbox::className(), [
		'value' => 1
	]) ?>

    <div class="form-group">
        <br>
		<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
