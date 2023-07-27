<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\AdminHelper;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use common\models\Partner;
use backend\widgets\MetronicSingleCheckbox;

/* @var $this yii\web\View */
/* @var $model \backend\models\Partner */
/* @var $form yii\widgets\ActiveForm */
/* @var array $locationRegion \common\models\LocationRegion */

?>
<?php $form = ActiveForm::begin(); ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= \common\helpers\AdminHelper::getProjectWidget($form, $model, 'projects', true) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'type')->dropDownList(
			ArrayHelper::map(Yii::$app->componentHelper->getStatus(), 'value', 'label'),
			[
				'prompt' => 'Укажите тип',
			]
		) ?>
    </div>
    <div class="col like-box">
		<?= $form->field($model, 'status')->widget(MetronicSingleCheckbox::className(), [
			'value' => Partner::ENABLE,
			'label' => 'Видимый',
		]) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'location_region_id')->dropDownList(
			ArrayHelper::map($locationRegion, 'id', 'native_name'),
			[
				'prompt' => 'Выберите область',
			]
		) ?>
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
		<?php foreach (Yii::$app->languages->languages as $language) : ?>
			<?= $form->field($model, AdminHelper::getLangField('address', $language))->textInput(['maxlength' => true]) ?>
		<?php endforeach ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'coordinate')->textInput(['maxlength' => true]) ?>
    </div>
</div>


<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'phones')->widget(MultipleInput::className(), [
			'allowEmptyList'    => false,
			'enableGuessTitle'  => false,
			'sortable' => true,
			'columns' => [
				[
					'name'  => 'name',
					'title' => 'Название',
				],
			]
		]) ?>
        <hr>
		<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php if (!empty($model->logotype)) : ?>
			<?= Html::img($model->getThumbUploadUrl('logotype', 'thumb'), ['class' => 'img-thumbnail']); ?>
		<?php endif ?>
		<?= $form->field($model, 'logotype')->fileInput() ?>
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
