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

	<?= $form->field($model, 'name') ?>

	<?= $form->field($model, 'project_id')->widget(\kartik\select2\Select2::className(), [
		'data' => [
			          '' => Yii::t('app', 'All')
		          ] + \yii\helpers\ArrayHelper::map(Yii::$app->projects->projects, 'alias', 'name'),
	]) ?>

	<?= $form->field($model, 'status')->widget(\backend\widgets\MetronicSingleCheckbox::className(), [
		'value' => \backend\models\ProductType::STATUS_ACTIVE
	]) ?>

    <div class="form-group">
        <br>
		<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
