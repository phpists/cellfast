<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form \yii\bootstrap\ActiveForm */
/* @var $model \common\models\WriteToUsFeedback */
/* @var $modelID string */
/* @var $contactsData array[] */

?>
<br>
<h2><?= $contactsData['label'] ?></h2>

<div class="contacts__cnt">
	<div class="contacts__map-wrap">
		<div data-coords="<?= $contactsData['coordinate'] ?>" class="contactsMap contacts__map"></div>
		<div class="contacts__form">
			<div class="contacts__form__title"><span><?= Yii::t('app', 'Write to us') ?></span></div>

			<?php $form = ActiveForm::begin([
				'id' => $modelID,
				'action' => ['feedback/send-form'],
				'class' => 'contacts__form__form',
			]) ?>

			<?= Html::hiddenInput('model', base64_encode($model::className())) ?>

			<?= Html::hiddenInput('secret_form_key', '') ?>

			<div class="contacts__form__input">
				<?= $form->field($model, 'name', ['template' => '{input}'])->textInput([
					'placeholder' => Yii::t('app', 'Your name'),
				]) ?>
			</div>

			<div class="contacts__form__input">
				<?= $form->field($model, 'email', ['template' => '{input}'])->textInput([
					'placeholder' => Yii::t('app', 'Your email'),
				]) ?>
			</div>

			<div class="contacts__form__input">
				<?= $form->field($model, 'message', ['template' => '{input}'])->textarea([
					'rows' => 6,
					'placeholder' => Yii::t('app', 'Message text'),
				]) ?>
			</div>

			<div class="contacts__form__btn">
				<div class="btn btn_fw btn_blue btn-send-handler"><?= Yii::t('app', 'Submit') ?></div>
			</div>

			<div class="hidden">
				<?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn_fw btn_blue', 'name' => 'contact-button']) ?>
			</div>

			<?php ActiveForm::end(); ?>

		</div>
	</div>
</div>

<div class="contacts__info">
	<div class="contacts__info__row">
		<div class="contacts__info__coll">
			<div class="contacts__info__it">
				<div class="contacts__info__it__icon">
					<svg class="svg-icon location-icon">
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#location-icon"></use>
					</svg>
				</div>
				<div class="contacts__info__it__title"><span><?= Yii::t('app', 'Address') ?>:</span></div>
				<div class="contacts__info__it__txt"><span><?= $contactsData['location'] ?></span></div>
			</div>
		</div>
		<div class="contacts__info__coll">
			<div class="contacts__info__it">
				<div class="contacts__info__it__icon">
					<svg class="svg-icon phone-icon">
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#phone-icon"></use>
					</svg>
				</div>
				<div class="contacts__info__it__title"><span><?= Yii::t('app', 'Phones') ?>:</span></div>
				<div class="contacts__info__it__txt">
					<?php foreach ($contactsData['phone'] as $phone) : ?>
						<p><a href="tel:<?= $phone['url'] ?>"><?= $phone['anchor'] ?></a></p>
					<?php endforeach ?>
				</div>
			</div>
		</div>
		<div class="contacts__info__coll">
			<div class="contacts__info__it">
				<div class="contacts__info__it__icon">
					<svg class="svg-icon mail-icon">
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#mail-icon"></use>
					</svg>
				</div>
				<div class="contacts__info__it__title"><span>E-mail:</span></div>
				<div class="contacts__info__it__txt">
					<?php foreach ($contactsData['email'] as $email) : ?>
                        <p><a href="mailto:<?= $email['single_email'] ?>"><?= $email['single_email'] ?></a></p>
					<?php endforeach ?>
                </div>
			</div>
		</div>
		<div class="contacts__info__coll">
			<div class="contacts__info__it">
				<div class="contacts__info__it__icon">
					<svg class="svg-icon clock-icon">
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#clock-icon"></use>
					</svg>
				</div>
				<div class="contacts__info__it__title"><span><?= Yii::t('app', 'Working hours') ?>:</span></div>
				<div class="contacts__info__it__txt"><span><?= $contactsData['work_time'] ?></span></div>
			</div>
		</div>
	</div>
</div>
