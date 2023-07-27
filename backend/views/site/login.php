<?php

/**
 * @var $this yii\web\View
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \common\models\LoginForm
 * @var $resetModel \common\models\PasswordResetRequestForm
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="m-login__signin">
    <div class="m-login__logo">
        <a href="#">
            <img src="<?= Url::to('@web/images/noitclean.png') ?>">
        </a>
    </div>
    <div class="m-login__head">
        <h3 class="m-login__title"><?= Yii::t('app', 'Enter to the admin-panel')?></h3>
    </div>
	<?= \backend\widgets\MetronicAlert::widget()?>
	<?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'm-login__form m-form']]); ?>
    <div class="form-group m-form__group">
		<?= Html::activeInput('text', $model, 'email', ['class' => 'form-control m-input', 'placeholder' => Yii::t('app', 'Username'), 'autocomplete' => 'off'])?>
    </div>
    <div class="form-group m-form__group">
		<?= Html::activeInput('password', $model, 'password', ['class' => 'form-control m-input m-login__form-input--last', 'placeholder' => Yii::t('app', 'Password'), 'autocomplete' => 'off'])?>
    </div>
    <div class="row m-login__form-sub">
        <div class="col m--align-left">
            <label class="m-checkbox m-checkbox--focus">
				<?= Html::activeCheckbox($model, 'rememberMe', ['label' => false])?> <?= Yii::t('app', 'Remember me')?>
                <span></span>
            </label>
        </div>
        <div class="col m--align-right">
            <a href="javascript:;" id="m_login_forget_password" class="m-link"><?= Yii::t('app', 'Forget Password?')?></a>
        </div>
    </div>
    <div class="m-login__form-action">
        <button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air"><?= Yii::t('app', 'Login')?></button>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<div class="m-login__signup">
    <div class="m-login__head">
        <h3 class="m-login__title">Sign Up</h3>
        <div class="m-login__desc">Enter your details to create your account:</div>
    </div>
</div>
<div class="m-login__forget-password">
    <div class="m-login__head">
        <h3 class="m-login__title"><?= Yii::t('app', 'Forgotten Password?')?></h3>
        <div class="m-login__desc"><?= Yii::t('app', 'Enter your email to reset your password')?>:</div>
    </div>
</div>