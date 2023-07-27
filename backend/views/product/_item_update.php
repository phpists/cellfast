<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductItem */

?>

<div class="product-item-update-form">
    <?php $form = ActiveForm::begin([
        'id' => 'item-update-form',
        'action' => ['item-update', 'id' => $model->id, 'product_id' => $model->product_id],
    ]); ?>

    <?= $this->render('_form_item', [
        'model' => $model,
        'product_type' => $model->product->type,
        'form' => $form,
    ])?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Close'), '#', ['class' => 'btn btn-default cancel-action', 'onclick' => new \yii\web\JsExpression("$('#item-update-modal').modal('hide');return false;")]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
