<?php

use yii\helpers\Html;
use yii\helpers\Url;
use vova07\imperavi\Widget;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\helpers\AdminHelper;
use noIT\feature\widgets\FeatureWidgetHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductFeature */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>

<div class="<?= $__params['id']?>-form">

	<?php $form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data'],
	]); ?>

    <div class="row justify-content-between">
        <div class="col like-box">
			<?= AdminHelper::getProjectWidget($form, $model)?>

			<?= $form->field($model, 'native_name')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

			<?php foreach (Yii::$app->languages->languages as $language) : ?>
				<?= $form->field($model, AdminHelper::getLangField('name', $language))->textInput(['maxlength' => true]) ?>
			<?php endforeach ?>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col like-box">
			<?= AdminHelper::getSelectWidget($form, $model, 'filter_widget', ArrayHelper::map(FeatureWidgetHelper::getFilterWidgets(), 'id', 'label'))?>
        </div>
        <div class="col like-box">
			<?= AdminHelper::getStatusWidget($form, $model)?>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col like-box">
			<?= AdminHelper::getBooleanWidget($form, $model, 'overall')?>
        </div>
        <div class="col like-box">
			<?= AdminHelper::getBooleanWidget($form, $model, 'multiple')?>
        </div>
        <div class="col like-box">
			<?= AdminHelper::getSelectWidget($form, $model, 'value_type', FeatureWidgetHelper::getValueTypes())?>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col like-box">
			<?= AdminHelper::getImageUploadWidget($form, $model)?>
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
			<?= $this->render('_values', [
				'model' => $model,
				'form' => $form,
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
			<?php foreach (Yii::$app->languages->languages as $language) :?>
				<?= $form->field($model, "caption_{$language->suffix}")->widget(Widget::className(), [
					'settings' => [
						'lang' => 'ru',
						'minHeight' => 400,
						'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'image', 'link', 'alignment', 'horizontalrule'],
						'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
						'imageUpload' => Url::to(['product-feature/body-image-upload']),
						'imageManagerJson' => Url::to(['product-feature/body-images-get']),
						'imageDelete' => Url::to(['product-feature/body-file-delete']),
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

	<?php ActiveForm::end(); ?>

</div>
