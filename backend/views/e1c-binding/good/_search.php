<?php

use common\models\soap\E1cGroupOfGood;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductTypeSearch */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>

<div class="<?= $__params['id']?>-search">

    <?php $form = ActiveForm::begin([
        'action' => ['/e1c-binding/good'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'group_of_good_id')->dropDownList(ArrayHelper::map(E1cGroupOfGood::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'name') ?>

    <div class="form-group">
        <br>
		<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
