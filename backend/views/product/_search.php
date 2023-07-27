<?php

use backend\models\CategorySearch;
use backend\widgets\MetronicBoostrapSelect;
use backend\widgets\MetronicSingleCheckbox;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CategorySearch */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>

<div class="<?= $__params['id']?>-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'sku') ?>

	<?= $form->field($model, 'name') ?>

	<?= $form->field($model, 'type_id')->widget(\kartik\select2\Select2::className(), [
		'data' => ['' => Yii::t('app', 'All')] + \yii\helpers\ArrayHelper::map(\backend\models\ProductType::find()->orderBy(['name_ru_ru' => SORT_ASC])->all(), 'id', 'native_name'),
	]) ?>

	<?= $form->field($model, 'project_id')->widget(\kartik\select2\Select2::className(), [
		'data' => [
			          '' => Yii::t('app', 'All')
		          ] + \yii\helpers\ArrayHelper::map(Yii::$app->projects->projects, 'alias', 'name'),
	]) ?>

    <div class="form-group">
        <br>
		<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
