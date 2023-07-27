<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AboutMainPageSearch */
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

<?= $form->field($model, 'name_ru_ru') ?>

<?= $form->field($model, 'name_uk_ua') ?>

    <div class="form-group">
        <br>
		<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
    </div>

<?php ActiveForm::end(); ?>