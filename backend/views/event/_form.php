<?php

use yii\helpers\Html;
use yii\helpers\Url;
use vova07\imperavi\Widget;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\helpers\AdminHelper;


/* @var $this yii\web\View */
/* @var $model common\models\Event */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>

<?php $form = ActiveForm::begin([
	'options' => ['enctype' => 'multipart/form-data'],
]); ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= \common\helpers\AdminHelper::getProjectWidget($form, $model) ?>
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
		<?= AdminHelper::getStatusWidget($form, $model, null, \common\models\Event::STATUS_ACTIVE)?>
    </div>
    <div class="col like-box">
		<?= AdminHelper::getDateWidget($form, $model, AdminHelper::FIELDNAME_PUBLISHED)?>
    </div>
</div>


<div class="row justify-content-between">
    <div class="col like-box">
		<?php
		$langs = [];
		foreach (Yii::$app->languages->languages as $language) {
			$langs[] = Yii::t('app', $language->code);
		}
		?>
		<?= $form->field($model, 'rel_tags')->widget(\dosamigos\selectize\SelectizeDropDownList::className(), [
			'items' => ArrayHelper::map(\common\models\Tag::all($model->project_id), 'id', AdminHelper::getLangField('name')),
			'clientOptions' => [
				'valueField' => 'id',
				'labelField' => 'name',
				'searchField' => 'name',
				'create' => new \yii\web\JsExpression('function(input, callback) {
                        $.ajax({
                            url: \''. \yii\helpers\Url::to(['tag/add']) .'\',
                            data: {project: $(\'#event-project_id\').val(), value: input},
                            type: \'post\',
                            dataType: \'json\',
                            success: function(data) {
                                
                                callback(data);
                            }
                       });
                    }'),
			],
			'options' => [
				'placeholder' => Yii::t('app', 'Select or add tags (for few langs: {langs})', ['langs' => implode('|', $langs)]),
				'multiple' => true,
			],
		])?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'rel_categories')->widget(Select2::className(), [
			'data' => Yii::$app->category->getCategoriesFloatListTree(),
			'language' => Yii::$app->language,
			'options' => [
				'placeholder' => Yii::t('app', 'Select categories'),
				'multiple' => true,
			],
			'pluginOptions' => [
				'allowClear' => true
			],
		])?>
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

<div class="row justify-content-between">
    <div class="col like-box">
		<?= AdminHelper::getImageUploadWidget($form, $model, 'image') ?>
    </div>
    <div class="col like-box">
		<?= AdminHelper::getImageUploadWidget($form, $model, 'image_list') ?>
    </div>
</div>

<?php if (!$model->isNewRecord) : ?>
    <div class="row justify-content-between">
        <div class="col like-box">
			<?= AdminHelper::getImagesUploadWidget($model)?>
        </div>
    </div>
<?php endif?>

<div class="row justify-content-between">
    <div class="col">
        <div class="form-group">
            <br>
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php foreach (Yii::$app->languages->languages as $language) : ?>
			<?= $form->field($model, "teaser_{$language->suffix}")->widget(Widget::className(), [
				'settings' => [
					'lang' => 'ru',
					'minHeight' => 400,
					'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'image', 'link', 'alignment', 'horizontalrule'],
					'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
					'imageUpload' => Url::to(['event/body-image-upload']),
					'imageManagerJson' => Url::to(['event/body-images-get']),
					'imageDelete' => Url::to(['event/body-file-delete']),
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

		<?php foreach (Yii::$app->languages->languages as $language) : ?>
			<?= $form->field($model, "body_{$language->suffix}")->widget(Widget::className(), [
				'settings' => [
					'lang' => 'ru',
					'minHeight' => 400,
					'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'image', 'link', 'alignment', 'horizontalrule'],
					'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
					'imageUpload' => Url::to(['event/body-image-upload']),
					'imageManagerJson' => Url::to(['event/body-images-get']),
					'imageDelete' => Url::to(['event/body-file-delete']),
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

<div class="row justify-content-between">
    <div class="col like-box">
		<?php foreach (Yii::$app->languages->languages as $language) :?>
			<?= $form->field($model, "seo_h1_{$language->suffix}")->textInput(['maxlength' => true]) ?>
		<?php endforeach?>

		<?php foreach (Yii::$app->languages->languages as $language) :?>
			<?= $form->field($model, "seo_title_{$language->suffix}")->textInput(['maxlength' => true]) ?>
		<?php endforeach?>

		<?php foreach (Yii::$app->languages->languages as $language) :?>
			<?= $form->field($model, "seo_description_{$language->suffix}")->textarea(['rows' => 6]) ?>
		<?php endforeach?>

		<?php foreach (Yii::$app->languages->languages as $language) :?>
			<?= $form->field($model, "seo_keywords_{$language->suffix}")->textarea(['rows' => 6]) ?>
		<?php endforeach?>
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
