<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $this \yii\web\View */
/** @var $model \common\components\GdsCalc\models\OrderForm */

$js = <<<JS
$('#gdscalc-order').on('beforeSubmit', function() {
    
    return false;
});
JS;

?>
<div class="consult-popup">
    <div class="row">
        <div class="col-md-12">
            <p>Пожалуйста, введите контактные данные.</p>
            <p>После оформления заявки наш менеджер свяжется с вами для уточнения деталей в кротчайшие сроки!</p>

            <div style="text-align: center">
                    <div class="home_form">
                        <?php $form = ActiveForm::begin([
                            'action' => ['gdscalc/order'],
                            'id' => 'gdscalc-order',
                        ]) ?>

                        <?= Html::hiddenInput('send', 1) ?>

                        <?= Html::hiddenInput('data', json_encode($model->data)) ?>

                        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'телефон *'])->label(false) ?>

                        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'имя'])->label(false) ?>

                        <?= Html::submitButton('Заказать', ['id' => 'sendq']) ?>

                        <?php ActiveForm::end(); ?>
                    </div>
        </div>
    </div>
</div>
