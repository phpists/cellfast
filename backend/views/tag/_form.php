<?php

use yii\helpers\Html;
use yii\helpers\Url;
use vova07\imperavi\Widget;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'project_id')->widget(Select2::className(), [
				'data' => \yii\helpers\ArrayHelper::map(Yii::$app->projects->projects, 'alias', 'name'),
				'language' => Yii::$app->language,
				'options' => [
					'placeholder' => Yii::t('app', 'Select project'),
					'multiple' => false,
				],
				'pluginOptions' => [
				],
			]
		)?>
    </div>
    <div class="col like-box">
		<?= $form->field($model, 'status')->checkbox([
			'value' => \common\models\Article::STATUS_ACTIVE
		]) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php foreach (Yii::$app->languages->languages as $language) : ?>
			<?= $form->field($model, "name_{$language->suffix}")->textInput(['maxlength' => true]) ?>
		<?php endforeach ?>

		<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php foreach (Yii::$app->languages->languages as $language) : ?>
			<?= $form->field($model, "caption_{$language->suffix}")->widget(Widget::className(), [
				'settings' => [
					'lang' => 'ru',
					'minHeight' => 400,
					'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'image', 'link', 'alignment', 'horizontalrule'],
					'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
					'imageUpload' => Url::to(['tag/body-image-upload']),
					'imageManagerJson' => Url::to(['tag/body-images-get']),
					'imageDelete' => Url::to(['tag/body-file-delete']),
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

<br>

<?php ActiveForm::end(); ?>
