<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

\cellfast\assets\ContentAsset::register($this);

$this->title = Yii::t('app', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="main-content">
    <section class="content signup-view">
        <div class="container">
			<?= \cellfast\widgets\Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				'options' => ['class'=>'']
			]) ?>

			<?= \cellfast\widgets\Alert::widget() ?>

            <div class="content__body signup-view-wrapper">
                <div class="content__title"><h1><?= Html::encode($this->title) ?></h1></div>

                <div class="row">
                    <div class="col-lg-12">
                        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                            <?= $form->field($model, 'email')->input('email', ['autofocus' => true]) ?>

                            <?= $form->field($model, 'name')->textInput() ?>

                            <?= $form->field($model, 'password')->passwordInput() ?>

                            <div class="form-group submit-box">
                                <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                            </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
