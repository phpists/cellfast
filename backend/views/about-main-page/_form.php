<?php

use yii\helpers\Html;
use noIT\core\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AboutMainPage */
/* @var $form yii\widgets\ActiveForm */

$lang_attributes = ['ru_ru', 'uk_ua'];

?>

<?php $form = ActiveForm::begin(); ?>

<div class="custom-form-section">
    <div class="custom-form-section-box">

        <div class="row justify-content-between">
            <div class="col like-box">
				<?= \common\helpers\AdminHelper::getProjectWidget($form, $model) ?>
            </div>
        </div>

        <div class="row justify-content-between">
            <div class="col like-box">
				<?php foreach($lang_attributes as $attribute) : ?>
					<?= $form->field($model, "name_{$attribute}")->textInput() ?>
				<?php endforeach ?>
            </div>
        </div>

        <div class="row justify-content-between">
            <div class="col like-box">
				<?php if(!empty($model->cover)) : ?>
					<?= Html::img($model->getUploadUrl('cover'), ['class' => 'img-thumbnail']); ?>
				<?php endif ?>
				<?= $form->field($model, 'cover')->fileInput() ?>
            </div>
        </div>

        <hr>

        <div class="row justify-content-between">
            <div class="col like-box">
				<?php foreach($lang_attributes as $attribute) : ?>
					<?= $form->field($model, "body_{$attribute}")->editor([
						'settings' => [
							'lang' => 'ru',
							'minHeight' => 400,
							'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'link', 'alignment', 'horizontalrule'],
							'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
							'plugins' => [
								'fullscreen',
								'table',
							],
						]
					]) ?>
				<?php endforeach ?>
            </div>
        </div>

    </div>
</div>

<div class="custom-form-section" style="margin-top: 32px;">

    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                    <h3 class="m-portlet__head-text">Иформационный блок -&nbsp;<a href="http://joxi.ru/J2bbx6Qc0E3BZ2"> (скриншот)</a></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="custom-form-section-box">

        <div class="row justify-content-between">
            <div class="col like-box">
				<?php foreach($lang_attributes as $attribute) : ?>
					<?= $form->field($model, "info_teaser_1_{$attribute}")->editor([
						'settings' => [
							'lang' => 'ru',
							'minHeight' => 210,
							'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'link', 'alignment', 'horizontalrule'],
							'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
							'plugins' => [
								'fullscreen',
								'table',
							],
						]
					]) ?>
				<?php endforeach ?>
            </div>
            <div class="col like-box">
				<?php if(!empty($model->info_image_1)) : ?>
					<?= Html::img($model->getUploadUrl('info_image_1'), ['class' => 'img-thumbnail']); ?>
				<?php endif ?>
				<?= $form->field($model, 'info_image_1')->fileInput() ?>
            </div>
        </div>

        <div class="row justify-content-between">
            <div class="col like-box">
				<?php if(!empty($model->info_image_2)) : ?>
					<?= Html::img(Yii::getAlias("@cdnUrl/about-us-main-page/{$model['info_image_2']}"), ['class' => 'img-thumbnail']); ?>
				<?php endif ?>
				<?= $form->field($model, 'info_image_2')->fileInput() ?>
            </div>
            <div class="col like-box">
				<?php foreach($lang_attributes as $attribute) : ?>
					<?= $form->field($model, "info_teaser_2_{$attribute}")->editor([
						'settings' => [
							'lang' => 'ru',
							'minHeight' => 210,
							'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'link', 'alignment', 'horizontalrule'],
							'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
							'plugins' => [
								'fullscreen',
								'table',
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
					<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php ActiveForm::end(); ?>

