<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'Sign In');

$fieldOptions1 = [
	'options' => ['class' => 'form-group has-feedback'],
	'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
	'options' => ['class' => 'form-group has-feedback'],
	'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">

    <div class="login-logo">
        <a href="#"><b>Cellfast</b></a>
    </div>

    <div class="login-box-body">
		<?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

		<?= $form
			->field($model, 'email', $fieldOptions1)
			->label(false)
			->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>

		<?= $form
			->field($model, 'password', $fieldOptions2)
			->label(false)
			->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <div class="row">
            <div class="col-xs-8">
				<?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <div class="col-xs-4">
				<?= Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        </div>

		<?php ActiveForm::end(); ?>

		<?= Html::a(Yii::t('app', 'I forgot my password'), ['user/default/request-reset-password']) ?></a>

    </div>

</div>
