<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="main-content">
    <section class="content signin-view">
        <div class="container">

			<?= \ines\widgets\Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				'options' => ['class'=>'']
			]) ?>

			<?= \ines\widgets\Alert::widget() ?>

            <div class="content__body signin-view-wrapper">
                <div class="content__title">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                                <?= $form->field($model, 'password')->passwordInput() ?>

                                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                                <div>
                                    <?= Html::a(Yii::t('app', 'Forgot password?'), ['user/request-password-reset'], ['class' => 'reset-password-link']) ?>
                                </div>

                                <div class="form-group submit-box">
                                    <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                                </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
            </div>

        </div>
    </section>
</section>
