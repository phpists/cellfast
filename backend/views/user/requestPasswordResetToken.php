<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \noIT\user\models\PasswordResetRequestForm */

$this->title = Yii::t('app', 'Request password reset');

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
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p><?= Yii::t('app', 'Please fill out your email. A link to reset password will be sent there.')?></p>

        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

        <?= $form
            ->field($model, 'email')
            ->label(false)
            ->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('email')]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
