<?php

use noIT\core\helpers\Url;
use backend\models\Category;
use common\helpers\AdminHelper;
use common\models\ProductType;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;
use vova07\imperavi\Widget;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */

$__params = require __DIR__ .'/__params.php';
?>

<?php $form = ActiveForm::begin([
	'options' => ['enctype' => 'multipart/form-data'],
]); ?>

    <div class="row justify-content-between">
        <div class="col like-box">
			<?= AdminHelper::getProjectWidget($form, $model) ?>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col like-box">
			<?= $form->field($model, 'parent_id')->widget(Select2::className(), [
					'data' => [0 => Yii::t('app', '-- {label} --', ['label' => Yii::t('app', 'Root')])] + Yii::$app->category->getCategoriesFloatListTree(),
					'language' => Yii::$app->language,
					'options' => [
						'placeholder' => Yii::t('app', 'Select category'),
						'multiple' => false,
					],
					'pluginOptions' => [],
				]
			)?>
        </div>
        <div class="col like-box">
			<?= AdminHelper::getSelectWidget($form, $model, 'product_type_id', ['' => Yii::t('app', '-- {label} --', ['label' => Yii::t('app', 'null')])] + ArrayHelper::map(ProductType::find()->all(), 'id', 'native_name')) ?>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col like-box">
			<?= $form->field($model, 'native_name')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'slug_outer')->textInput(['maxlength' => true]) ?>
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
			<?= AdminHelper::getImageUploadWidget($form, $model)?>
        </div>
        <div class="col like-box">
            <?php foreach (Yii::$app->languages->languages as $language) : ?>
                <?= $form->field($model, AdminHelper::getLangField('video', $language))->textInput(['maxlength' => true]) ?>
            <?php endforeach ?>
        </div>
        <div class="col like-box">
			<?= AdminHelper::getStatusWidget($form, $model, null, Category::STATUS_ACTIVE)?>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col like-box">
        <?php foreach (Yii::$app->languages->languages as $language) : ?>
            <?= $form->field($model, AdminHelper::getLangField('manuals', $language))->widget(MultipleInput::className(), [
                'allowEmptyList'    => false,
                'enableGuessTitle'  => false,
                'sortable' => true,
                'columns' => [
                    [
                        'name' => 'title',
                        'title' => 'Название',
                    ],
                    [
                        'name' => 'body',
                        'title' => 'Текст инструкции',
                        'type'  => Widget::className(),
                        'options' => [
                            'settings' => [
                                'lang' => 'ru',
                                'minHeight' => 300,
                                'imageUpload' => Url::to(['category/body-image-upload']),
                                'imageManagerJson' => Url::to(['category/body-images-get']),
                                'imageDelete' => Url::to(['category/body-file-delete']),
                                'plugins' => [
                                    'fullscreen',
                                    'imagemanager'
                                ],
                            ],
                            'plugins' => [
                                'imagemanager' => 'vova07\imperavi\bundles\ImageManagerAsset',
                            ],
                        ],
                    ],
                ]
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
			<?php foreach (Yii::$app->languages->languages as $language) : ?>
				<?= $form->field($model, "description_{$language->suffix}")->widget(Widget::className(), [
					'settings' => [
						'lang' => 'ru',
						'minHeight' => 300,
						'imageUpload' => Url::to(['category/body-image-upload']),
						'imageManagerJson' => Url::to(['category/body-images-get']),
						'imageDelete' => Url::to(['category/body-file-delete']),
						'plugins' => [
							'fullscreen',
							'imagemanager'
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
			<?php endforeach ?>
			<?php foreach (Yii::$app->languages->languages as $language) :?>
				<?= $form->field($model, "seo_title_{$language->suffix}")->textInput(['maxlength' => true]) ?>
			<?php endforeach ?>
			<?php foreach (Yii::$app->languages->languages as $language) :?>
				<?= $form->field($model, "seo_description_{$language->suffix}")->textarea(['rows' => 6]) ?>
			<?php endforeach ?>
			<?php foreach (Yii::$app->languages->languages as $language) :?>
				<?= $form->field($model, "seo_keywords_{$language->suffix}")->textarea(['rows' => 6]) ?>
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
