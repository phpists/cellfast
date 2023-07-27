<?php

use common\helpers\AdminHelper;
use common\models\LocationPlace;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Warehouse */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>
<?php $form = ActiveForm::begin(); ?>

    <div class="row justify-content-between">
        <div class="col like-box">
			<?= $form->field($model, 'native_name')->textInput(['maxlength' => true]) ?>

			<?= AdminHelper::getSelectWidget($form, $model, 'location_place_id', ArrayHelper::map(\common\models\LocationPlace::find()->select(['id', 'native_name'])->asArray()->all(), 'id', 'native_name'))?>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col like-box">
			<?= $form->field($model, 'lng')->input('number', ['step' => 0.000001]) ?>
			<?= $form->field($model, 'lat')->input('number', ['step' => 0.000001]) ?>
			<?php // TODO - Add sort widget ?>
			<?= $form->field($model, 'sort_order')->input('number', ['step' => 1]) ?>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col">
            <div class="form-group">
                <br>
				<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>