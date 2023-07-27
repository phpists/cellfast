<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\AdminHelper;
use common\models\Document;
use yii\helpers\ArrayHelper;
use backend\widgets\MetronicSingleCheckbox;

/* @var $this yii\web\View */
/* @var $model backend\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row justify-content-between">
    <div class="col like-box">
	    <?= \common\helpers\AdminHelper::getProjectWidget($form, $model) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'type')->dropDownList(
			ArrayHelper::map(Yii::$app->params['documentType'], 'value', 'label'), [
				'prompt' => 'Выберите тип'
			]
		) ?>
    </div>
    <div class="col like-box">
		<?= $form->field($model, 'status')->widget(MetronicSingleCheckbox::className(), [
		        'value' => Document::ENABLE,
                'label' => 'Видимый'
        ]) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php foreach (Yii::$app->languages->languages as $language) : ?>
			<?= $form->field($model, AdminHelper::getLangField('name', $language))->textInput(['maxlength' => true]) ?>
		<?php endforeach ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php foreach (Yii::$app->languages->languages as $language) : ?>
			<?= $form->field($model, AdminHelper::getLangField('caption', $language))->textInput(['maxlength' => true]) ?>
		<?php endforeach ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php if (!empty($model->cover_image)) : ?>
			<?= Html::img($model->getThumbUploadUrl('cover_image', 'thumb_book'), ['class' => 'img-thumbnail']); ?>
		<?php endif ?>
		<?= $form->field($model, 'cover_image')->fileInput() ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php if (!empty($model->file)) : ?>
			<?= Html::a('Смотреть', $model->getUploadUrl('file', 'thumb'), ['target' => '_blank']); ?>
            <hr>
		<?php endif ?>
		<?= $form->field($model, 'file')->fileInput() ?>
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

<br>

<?php ActiveForm::end(); ?>
