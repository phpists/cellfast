<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\MetronicSingleCheckbox;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';

?>

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

<?= $form->field($model, 'status')->widget(MetronicSingleCheckbox::className(), [
	'value' => \common\models\Category::STATUS_ACTIVE,
	'label' => 'Видимый',
]) ?>

<div class="form-group">
    <br>
	<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
	<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
</div>

<?php ActiveForm::end(); ?>

