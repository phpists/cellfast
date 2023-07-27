<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Emails - для отправки форм на почту';

?>

<?php $form = ActiveForm::begin(); ?>

    <div class="custom-form-section">

        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                        <h3 class="m-portlet__head-text">"Emails" - для отправки форм на почту</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-form-section-box">

            <div class="row justify-content-between">
                <div class="col like-box">
					<?= $form->field($model, 'email__cellfast')->textInput() ?>
					<?= $form->field($model, 'email__bryza')->textInput() ?>
					<?= $form->field($model, 'email__ines')->textInput() ?>
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

    <br>
<?php ActiveForm::end(); ?>