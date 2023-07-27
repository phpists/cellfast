<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\AdminHelper;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductItemsCsvForm */
/* @var $form yii\widgets\ActiveForm */
$__params = require __DIR__ .'/__params.php';
?>

<div class="<?= $__params['id']?>-items-form">

    <?= Html::activeHiddenInput($model, 'product_id')?>

    <div class="row">
        <div class="col-md-12">
            <div class="bs-callout bs-callout-base">
                <?= $form->field($model, 'delimiter')->dropDownList($model->delimiters) ?>

                <div class="form-group field-productitemscsvform-data">
                    <label class="control-label"><?= Yii::t('app', 'Format')?></label>
                    <div>
                        <?= $model->formatCaption ?>
                    </div>
                </div>

                <?= $form->field($model, 'data')->textarea(['rows' => 10])?>
            </div>
        </div>
    </div>
</div>
