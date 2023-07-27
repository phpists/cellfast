<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\MetronicBoostrapSelect;

/* @var $this yii\web\View */
/* @var $model \backend\models\FeedbackSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
	'action' => ['index'],
	'method' => 'get',
]); ?>

<?= $form->field($model, 'date_from') ?>

<?= $form->field($model, 'date_to') ?>

<?= $form->field($model, 'name') ?>

<?= $form->field($model, 'email') ?>

<?= $form->field($model, 'status')->widget(MetronicBoostrapSelect::className(), [
	'items' => Yii::$app->params['lead-status'],
    'prompt' => 'Выберите статус',
]) ?>

<div class="form-group">
    <br>
	<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
	<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
</div>

<?php ActiveForm::end(); ?>
