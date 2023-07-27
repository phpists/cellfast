<?php

/** @var $this \yii\web\View */
/** @var $model \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseE */
/** @var $component \common\components\GdsCalc\components\GdsCalcDrain */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin([
    'action' => ['gdscalc/calc'],
    'options' => [
        'class' => 'gdscalc-form'. $model->alias,
//        'enctype' => 'multipart/form-data'
    ]
]); ?>

<?= Html::hiddenInput('component', $component->alias) ?>

<?= Html::hiddenInput('model', $model->alias) ?>

<div class="row">
    <div class="col-md-5">
        <div class="gdscalc-formpreview gdscalc-formpreview-<?= $model->alias?>"></div>
    </div>
    <div class="col-md-7">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'sideA')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 1]) ?>
                <?= $form->field($model, 'sideB')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 2]) ?>
                <?= $form->field($model, 'sideC')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 3]) ?>
                <?= $form->field($model, 'sideD')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 4]) ?>
                <?= $form->field($model, 'sideE')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 5]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'sideF')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 6]) ?>
                <?= $form->field($model, 'sideG')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 7]) ?>
                <?= $form->field($model, 'sideJ')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 9]) ?>
                <?= $form->field($model, 'sideK')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 10]) ?>
                <?= $form->field($model, 'height')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 8]) ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo Html::submitButton(\Yii::t('app', 'Рассчитать'), ['class' => 'btn btn-primary btn_blue','id'=>'submit_id']) ?>
        </div>
    </div>
    <div class="clr"></div>
</div>

<?php ActiveForm::end(); ?>
