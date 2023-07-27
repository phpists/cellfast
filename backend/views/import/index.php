<?php

use yii\widgets\ActiveForm;
use noIT\core\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model \backend\models\ImportForm
 */

$this->title = 'Импорт цен через CSV-файл';
$this->params['breadcrumbs'][] = Yii::t('app', 'Импорт цен через CSV-файл');
?>
<div class="custom-form-section">
    <div class="custom-form-section-box">

        <?php if ($model->result) :?>
            <div class="row justify-content-between">
                <div class="col like-box">
                    <ul>
                   <?php foreach ($model->result as $item) :?>
                    <li><?= $item ?></li>
                   <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif;?>

        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <div class="row justify-content-between">
            <div class="col like-box">
                <?= $form->field($model, 'delimiter')->dropDownList([
                    ';' => 'Точка с запятой',
                    ',' => 'Запятая',
                ]) ?>
            </div>
        </div>

        <div class="row justify-content-between">
            <div class="col like-box">
                <?= $form->field($model, 'file')->fileInput() ?>
            </div>
        </div>

        <div class="row justify-content-between">
            <div class="col">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Import'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
