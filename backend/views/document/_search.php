<?php

use backend\widgets\MetronicBoostrapSelect;
use backend\widgets\MetronicSingleCheckbox;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DocumentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'type')->widget(MetronicBoostrapSelect::className(), [
		'items' => ArrayHelper::map(Yii::$app->params['documentType'], 'value', 'label'),
		'prompt' => 'Выберите тип',
	])?>

	<?= $form->field($model, 'project_id')->widget(\kartik\select2\Select2::className(), [
		'data' => [
			          '' => Yii::t('app', 'All')
		          ] + \yii\helpers\ArrayHelper::map(Yii::$app->projects->projects, 'alias', 'name'),
	]) ?>

	<?= $form->field($model, 'status')->widget(MetronicSingleCheckbox::className(), [
	        'label' => 'Видимый',
            'value' => \common\models\Document::ENABLE,
    ])?>

    <div class="form-group">
        <br>
		<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Сбросить', Url::canonical(), ['class' => 'btn btn-default']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
