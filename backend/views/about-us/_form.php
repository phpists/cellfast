<?php

use yii\helpers\Html;
use noIT\core\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AboutUs */
/* @var $form yii\widgets\ActiveForm */

$lang_attributes = ['ru_ru', 'uk_ua'];

?>

<?php $form = ActiveForm::begin(); ?>

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
        <?= $form->field($model, "slug")->textInput() ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
        <?php foreach($lang_attributes as $attribute) : ?>

            <?= $form->field($model, "teaser_{$attribute}")->editor([
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 160,
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
    <div class="col like-box">
        <?php if(!empty($model->cover)) : ?>
            <?= Html::img($model->getUploadUrl('cover'), ['class' => 'img-thumbnail']); ?>
        <?php endif ?>

        <?= $form->field($model, 'cover')->fileInput() ?>
    </div>
    <div class="col like-box">
        <?php if(!empty($model->cover_2)) : ?>
            <?= Html::img($model->getUploadUrl('cover_2'), ['class' => 'img-thumbnail']); ?>
        <?php endif ?>

        <?= $form->field($model, 'cover_2')->fileInput() ?>
    </div>
</div>

<div class="row justify-content-between">
    <div class="col like-box">
        <?php foreach($lang_attributes as $attribute) : ?>

            <?= $form->field($model, "teaser_2_{$attribute}")->editor([
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 160,
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
    <div class="col like-box">
        <?php foreach($lang_attributes as $attribute) : ?>

            <?= $form->field($model, "teaser_3_{$attribute}")->editor([
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 160,
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

<hr>

<div class="row justify-content-between">
    <div class="col like-box">
        <?php foreach($lang_attributes as $attribute) : ?>
            <?= $form->field($model, "body_{$attribute}")->editor([
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 260,
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
    <div class="col like-box">
        <?php foreach($lang_attributes as $attribute) : ?>
            <?= $form->field($model, "body_2_{$attribute}")->editor([
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

<div class="row justify-content-between">
    <div class="col like-box">
        <?= $form->field($model, "video")->textInput() ?>
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

