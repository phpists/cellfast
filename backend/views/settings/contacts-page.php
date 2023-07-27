<?php

use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Страница "Контактов"';

?>

<?php $form = ActiveForm::begin(); ?>

<?php foreach(Yii::$app->componentHelper->getProjects() as $project) : ?>

    <div class="custom-form-section">

        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
						<i class="la la-gear"></i>
						</span>
                        <h3 class="m-portlet__head-text">Страница контактов проекта: (<?= $project['name'] ?>)</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-form-section-box">

            <div class="row justify-content-between">
                <div class="col like-box">
					<?= $form->field($model, "contact__{$project['alias']}")->widget(MultipleInput::className(), [
						'allowEmptyList' => true,
						'enableGuessTitle' => false,
						'sortable' => true,
						'addButtonPosition' => MultipleInput::POS_FOOTER,
						'rendererClass' => \noIT\multipleInput\renderers\ListRenderer::className(),
						'columns' => [
							[
								'name' => 'label_ru_ru',
								'title' => 'Заголовок (Русский)',
							],
							[
								'name' => 'label_uk_ua',
								'title' => 'Заголовок (Украинский)',
							],
							[
								'name' => 'coordinate',
								'title' => 'Координаты',
							],
							[
								'name' => 'location_ru_ru',
								'title' => 'Адрес (Русский)',
							],
							[
								'name' => 'location_uk_ua',
								'title' => 'Адрес (Украинский)',
							],
							[
								'name' => 'phone',
								'title' => ' Телефоны',
								'type'  => MultipleInput::className(),
								'options' => [
									'columns' => [
										[
											'name' => 'anchor',
											'title' => 'Телефон (для людей)',
											'type' =>  MultipleInputColumn::TYPE_TEXT_INPUT,
										],
										[
											'name' => 'url',
											'title' => 'Телефон (ссылка)',
										],
									],
								],
							],
							[
								'name' => 'email',
								'title' => 'Email',
								'type'  => MultipleInput::className(),
								'options' => [
									'columns' => [
										[
											'name' => 'single_email',
											'title' => 'Email',
											'type' =>  MultipleInputColumn::TYPE_TEXT_INPUT
										],
										[
											'name' => 'not_usable_field',
											'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
										]
									],
								],
							],
							[
								'name' => 'work_time',
								'title' => 'Время работы',
							],
						]
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

        </div>
    </div>

    <br>
    <br>

<?php endforeach ?>

<?php ActiveForm::end(); ?>