<?php

use common\helpers\AdminHelper;
use common\models\LocationPlace;
use yii\helpers\Html;
use yii\helpers\Url;
use vova07\imperavi\Widget;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\*/
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'native_name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

		<?= AdminHelper::getSelectWidget($form, $model, 'region_id', ArrayHelper::map(\common\models\LocationRegion::find()->select(['id', 'native_name'])->asArray()->all(), 'id', 'native_name'))?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php foreach (Yii::$app->languages->languages as $language) :?>
			<?= $form->field($model, AdminHelper::getLangField('name', $language))->textInput(['maxlength' => true]) ?>
		<?php endforeach?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php if ($model->image) : ?>
			<?= Html::img($model->getThumbUploadUrl('image')) ?>
		<?php endif?>
		<?= $form->field($model, 'image')->fileInput() ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= AdminHelper::getBooleanWidget($form, $model, 'is_default', LocationPlace::STATUS_ACTIVE)?>
    </div>
    <div class="col like-box">
		<?= $form->field($model, 'sort_order')->input('number', ['step' => 1]) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php foreach (Yii::$app->languages->languages as $language) : ?>
			<?= $form->field($model, "body_{$language->suffix}")->widget(Widget::className(), [
				'settings' => [
					'lang' => 'ru',
					'minHeight' => 400,
					'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'image', 'link', 'alignment', 'horizontalrule'],
					'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
					'imageUpload' => Url::to(['location-place/body-image-upload']),
					'imageManagerJson' => Url::to(['location-place/body-images-get']),
					'imageDelete' => Url::to(['location-place/body-file-delete']),
					'plugins' => [
						'fullscreen',
						'table',
						'video',
					],
				],
				'plugins' => [
					'imagemanager' => 'vova07\imperavi\bundles\ImageManagerAsset',
				],
			]) ?>
		<?php endforeach ?>
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
