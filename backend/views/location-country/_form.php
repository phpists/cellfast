<?php

use yii\helpers\Html;
use yii\helpers\Url;
use vova07\imperavi\Widget;
use yii\widgets\ActiveForm;
use common\helpers\AdminHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductFeature */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'native_name')->textInput(['maxlength' => true]) ?>

		<?php foreach (Yii::$app->languages->languages as $language) : ?>
			<?= $form->field($model, AdminHelper::getLangField('name', $language))->textInput(['maxlength' => true]) ?>
		<?php endforeach ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php if ($model->image) : ?>
			<?= Html::img($model->getThumbUploadUrl('image')) ?>
		<?php endif ?>
		<?= $form->field($model, 'image')->fileInput() ?>
    </div>
</div>

<div class="row justify-content-between">
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
					'imageUpload' => Url::to(['location-country/body-image-upload']),
					'imageManagerJson' => Url::to(['location-country/body-images-get']),
					'imageDelete' => Url::to(['location-country/body-file-delete']),
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
