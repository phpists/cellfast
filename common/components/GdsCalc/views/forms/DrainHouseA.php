<?php

/** @var $this \yii\web\View */
/** @var $model \common\components\GdsCalc\models\drain\GdsCalcModelDrainHouseA */
/** @var $component \common\components\GdsCalc\components\GdsCalcDrain */

use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin([
    'action' => ['gdscalc/calc'],
    'options' => [
        'class' => 'gdscalc-form'. $model->alias,
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
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'sideC')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 3]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'height')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 4]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'height_full')->input('number', ['step' => 0.01, 'maxlength' => true, 'data-previewslide' => 5]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $this->render('_select-insulation', ['model' => $model, 'form' => $form]); ?>
            </div>
            <?php if (!empty($component->feature_type_options['gutter_bracket']) && count($component->feature_type_options['gutter_bracket']) > 1) :?>
            <div class="col-md-6">
                <?= $this->render('_select-install-type', ['model' => $model, 'form' => $form]); ?>
            </div>
            <?php endif ?>
        </div>

        <div class="form-group">
            <?php echo Html::submitButton(\Yii::t('app', 'Рассчитать'), ['class' => 'btn btn-primary btn_blue','id'=>'submit_id']) ?>
        </div>
    </div>
    <div class="clr"></div>
</div>

<?php ActiveForm::end(); ?>
