<?php

use backend\widgets\MetronicSingleCheckbox;
use common\helpers\AdminHelper;
use common\models\Document;
use noIT\core\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use vova07\imperavi\Widget;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $types \common\models\ProductType[] */
/* @var $form yii\widgets\ActiveForm */
$__params = require __DIR__ .'/__params.php';

$js = <<<JS
productItemDataModalForm($('#item-create-form'), $('#product-items-wrapper'), $('#item-create-modal'));
productItemDataModalForm($('#items-create-form'), $('#product-items-wrapper'), $('#items-create-modal'));
JS;

$this->registerJs($js);
?>

<?php $form = ActiveForm::begin([
	'options' => ['enctype' => 'multipart/form-data'],
]); ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php if ($model->type_id) : ?>
			<?= $form->field($model, 'type_id')->dropDownList( ArrayHelper::map($types, 'id', 'native_name'), ['disabled' => true])?>
			<?= Html::activeHiddenInput($model, 'type_id')?>
		<?php else : ?>
			<?= AdminHelper::getSelectWidget($form, $model, 'type_id', ArrayHelper::map($types, 'id', 'native_name'))?>
		<?php endif ?>
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
		<?= $form->field($model, 'native_name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

		<?php foreach (Yii::$app->languages->languages as $language) :?>
			<?= $form->field($model, AdminHelper::getLangField('name', $language))->textInput(['maxlength' => true]) ?>
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

<?php if (!$model->isNewRecord) : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="bs-callout bs-callout-media">
                <fieldset class="product-features">
                    <legend><?= Yii::t('app', 'Product items')?></legend>
					<?= Html::button('<i class="glyphicon glyphicon-plus"></i>', ['class' => 'btn btn-success', /*'data-toggle' => 'modal', 'data-target' => '#item-create-modal',*/ 'onclick' => '$(\'#item-create-modal\').modal(\'show\'); return false;'])?>
					<?= Html::button('<i class="glyphicon glyphicon-plus"></i><i class="glyphicon glyphicon-plus"></i>', ['class' => 'btn btn-success', /*'data-toggle' => 'modal', 'data-target' => '#items-create-modal',*/ 'onclick' => '$(\'#items-create-modal\').modal(\'show\'); return false;'])?>
                    <div id="product-items-wrapper">
						<?= $this->render('_items', [
							'model' => $model,
							'form' => $form,
						])?>
                    </div>
                </fieldset>
            </div>
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
<?php endif ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?= AdminHelper::getImageUploadWidget($form, $model, 'image')?>
    </div>
	<?php if (!$model->isNewRecord) : ?>
        <div class="col like-box">
			<?= AdminHelper::getImagesUploadWidget($model) ?>
        </div>
	<?php endif ?>
</div>

<div class="row justify-content-between">
    <div class="col">
        <div class="form-group">
            <br>
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php if ($model->type_id) : ?>
    <div class="row justify-content-between">
        <div class="col like-box">
            <fieldset class="product-features">
                <legend><?= Yii::t('app', 'Product features')?></legend>
                <div id="product-features-wrapper">
					<?= $this->render('_features', [
						'model' => $model,
						'form' => $form,
					]) ?>
                </div>
            </fieldset>

        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col like-box">

            <fieldset class="product-properties">
                <legend><?= Yii::t('app', 'Product properties')?></legend>
                <div id="product-properties-wrapper">
					<?= $this->render('_properties', [
						'model' => $model,
						'form' => $form,
					]) ?>
                </div>
            </fieldset>
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
<?php endif ?>

<div class="row justify-content-between">
    <div class="col like-box">
		<?php foreach (Yii::$app->languages->languages as $language) : ?>
			<?= $form->field($model, "teaser_{$language->suffix}")->widget(Widget::className(), [
				'settings' => [
					'lang' => 'ru',
					'minHeight' => 400,
					'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'image', 'link', 'alignment', 'horizontalrule'],
					'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
					'imageUpload' => Url::to(['product/body-image-upload']),
					'imageManagerJson' => Url::to(['product/body-images-get']),
					'imageDelete' => Url::to(['product/body-file-delete']),
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
					'imageUpload' => Url::to(['product/body-image-upload']),
					'imageManagerJson' => Url::to(['product/body-images-get']),
					'imageDelete' => Url::to(['product/body-file-delete']),
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

<?php if (!$model->isNewRecord) : ?>

	<?php
	/**
	 * Single create item modal form
	 */
	?>
	<?php Modal::begin([
		'id' => 'item-create-modal',
		'size' => Modal::SIZE_LARGE,
		'header' => "<h3>". Yii::t('app', 'Create product item') ."</h3>"
	]) ?>

	<?php $createForm = ActiveForm::begin([
		'id' => 'item-create-form',
		'action' => Url::to(['item-add', 'id' => $model->id]),
		'options' => ['enctype' => 'multipart/form-data'],
	]) ?>

	<?= $this->render('_form_item', [
		'model' => new \backend\models\ProductItem(['product_id' => $model->id]),
		'product_type' => $model->type,
		'form' => $createForm,
	]) ?>

    <div class="row justify-content-between">
        <div class="col">
            <div class="form-group">
                <br>
				<?= Html::submitButton(Yii::t('app', 'Create product item'), ['class' => 'btn btn-success']) ?>
				<?= Html::a(Yii::t('app', 'Close'), '#', ['class' => 'btn btn-default cancel-action', 'onclick' => new \yii\web\JsExpression("$('#item-create-modal').modal('hide');return false;")]) ?>
            </div>
        </div>
    </div>

	<?php ActiveForm::end(); ?>

	<?php Modal::end()?>

	<?php
	/**
	 * Multiple create item modal form
	 */
	?>
	<?php Modal::begin([
		'id' => 'items-create-modal',
		'size' => Modal::SIZE_LARGE,
		'header' => "<h3>". Yii::t('app', 'Create product item') ."</h3>"
	])?>

	<?php $createsForm = ActiveForm::begin([
		'id' => 'items-create-form',
		'action' => Url::to(['items-add', 'id' => $model->id]),
		'options' => ['enctype' => 'multipart/form-data'],
	]); ?>

	<?= $this->render('_form_items', [
		'model' => new \backend\models\ProductItemsCsvForm(['product_id' => $model->id]),
		'product_type' => $model->type,
		'form' => $createsForm,
	])?>

    <div class="row justify-content-between">
        <div class="col">
            <div class="form-group">
                <br>
				<?= Html::submitButton(Yii::t('app', 'Create product items'), ['class' => 'btn btn-success']) ?>
				<?= Html::a(Yii::t('app', 'Close'), '#', ['class' => 'btn btn-default cancel-action', 'onclick' => new \yii\web\JsExpression("$('#items-create-modal').modal('hide');return false;")]) ?>
            </div>
        </div>
    </div>

	<?php ActiveForm::end(); ?>

	<?php Modal::end()?>

	<?php
	/**
	 * Update item modal form
	 */
	?>
	<?php Modal::begin([
		'id' => 'item-update-modal',
		'size' => Modal::SIZE_LARGE,
		'header' => "<h3>". Yii::t('app', 'Update product item') ."</h3>"
	])?>

	<?php Modal::end()?>

<?php endif?>
