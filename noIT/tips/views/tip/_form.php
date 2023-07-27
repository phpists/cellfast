<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use vova07\imperavi\Widget;
use noIT\tips\models\Tip;

/* @var $this yii\web\View */
/* @var $model noIT\tips\models\Tip */
/* @var $form yii\widgets\ActiveForm */
/* @var array $tipModelsName */
/* @var array $tipModelAttributes */

$tipModule = noIT\tips\Module::getInstance();

?>

<?php $form = ActiveForm::begin(); ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= $form->field($model, 'model')->dropDownList($tipModelsName, [
			'id' => 'model-id',
			'prompt' => 'Выберите модель',
		]) ?>
    </div>
    <div class="col like-box">
		<?= $form->field($model, 'attribute')->widget(DepDrop::classname(), [
			'data' => $tipModelAttributes,
			'options'=> [
				'id' => 'attribute-id',
				'prompt' => 'Выберите атрибут модели',
			],
			'pluginOptions' => [
				'depends' => [
					'model-id'
				],
				'placeholder' => 'Выберите атрибут',
				'url' => Url::to(['tip/get-selected-model-attribute'])
			]
		]) ?>
    </div>
</div>

<div class="row justify-content-between" style="margin-top: 30px;">
    <div class="col like-box">
		<?= $form->field($model, 'body')->widget(Widget::className(), [
			'settings' => [
				'lang' => 'ru',
				'minHeight' => 200,
				'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'image', 'link', 'alignment', 'horizontalrule'],
				'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
				'imageUpload' => Url::to(['tip/body-image-upload']),
				'imageManagerJson' => Url::to(['tip/body-images-get']),
				'imageDelete' => Url::to(['tip/body-file-delete']),
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
    </div>
</div>

<div class="row justify-content-between">
    <div class="col">
        <div class="form-group">
            <br>
			<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

