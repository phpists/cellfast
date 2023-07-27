<?php

use common\helpers\AdminHelper;
use common\models\soap\E1cGroupOfGood;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductTypeSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $gridId string */


?>
<?php $form = ActiveForm::begin([
        'action' => ['good-update'],
        'id' => 'e1c-group-of-good-relation-dialog-form',
        'options' => [
            'class' => 'action-ajax-form'
        ],
])?>

<?= \kartik\widgets\Select2::widget([
    'name' => 'product_type_id',
    'data' => ArrayHelper::map(\common\models\ProductType::find()->all(), 'id', 'native_name'),
])?>

<div class="form-group">
	<?= Html::submitButton(Yii::t('app', 'Apply'), ['class' => 'btn btn-primary']) ?>
	<?= Html::resetButton(Yii::t('app', 'Reset form'), ['class' => 'btn btn-default']) ?>
</div>
<?php ActiveForm::end(); ?>


