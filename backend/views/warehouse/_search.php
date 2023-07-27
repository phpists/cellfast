<?php

use common\helpers\AdminHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductTypeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'native_name') ?>

	<?= AdminHelper::getSelectWidget($form, $model, 'location_country_id', ArrayHelper::map(\common\models\LocationCountry::find()->select(['id', 'native_name'])->asArray()->all(), 'id', 'native_name'))?>

	<?= AdminHelper::getSelectWidget($form, $model, 'location_region_id', ArrayHelper::map(\common\models\LocationRegion::find()->select(['id', 'native_name'])->asArray()->all(), 'id', 'native_name'))?>

	<?= AdminHelper::getSelectWidget($form, $model, 'location_place_id', ArrayHelper::map(\common\models\LocationPlace::find()->select(['id', 'native_name'])->asArray()->all(), 'id', 'native_name'))?>

    <div class="form-group">
        <br>
		<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
