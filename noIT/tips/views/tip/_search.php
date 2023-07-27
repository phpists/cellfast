<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model noIT\tips\models\TipSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
	'action' => ['index'],
	'method' => 'get',
]); ?>

<?= $form->field($model, 'id') ?>

<?= $form->field($model, 'model') ?>

<?= $form->field($model, 'attribute') ?>

<div class="form-group">
    <br>
	<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
	<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
</div>
<?php ActiveForm::end(); ?>
