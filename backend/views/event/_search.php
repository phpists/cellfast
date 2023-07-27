<?php

use backend\models\EventSearch;
use backend\widgets\MetronicBoostrapSelect;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\EventSearch */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>

<div class="<?= $__params['id']?>-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'project_id')->widget(\kartik\select2\Select2::className(), [
		'data' => [
			          '' => Yii::t('app', 'All')
		          ] + \yii\helpers\ArrayHelper::map(Yii::$app->projects->projects, 'alias', 'name'),
	]) ?>

	<?= $form->field($model, 'word') ?>

	<?= $form->field($model, 'slug') ?>

	<?= $form->field($model, 'status')->widget(\backend\widgets\MetronicSingleCheckbox::className(), [
		'value' => \backend\models\Category::STATUS_ACTIVE,
		'label' => 'Видимый',
	]) ?>

    <div class="form-group">
        <br>
		<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
