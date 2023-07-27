<?php

/** @var $this \yii\web\View */
/** @var $categories \common\models\Category */
/** @var $category \common\models\Category */
/** @var $documents \common\models\Document */
/** @var $document \common\models\Document */
/** @var $events \common\models\Event */
/** @var $event \common\models\Event */
/** @var $model \common\models\SubscribeFeedback */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<footer class="footer">
    <div class="container">
        <div class="footer__row">
            <div class="footer__col _sub">
                <div class="footer__list">
                    <div class="footer__list__title"><span><?= Yii::t('app', 'Subscription') ?></span></div>
                    <div class="footer__subs">

						<?php $form = ActiveForm::begin([
							'action' => ['feedback/send-form'],
							'id' => 'footerSubsForm'
						]); ?>

						<?= Html::hiddenInput('model', base64_encode($model::className())) ?>

						<?= Html::hiddenInput('secret_form_key', '') ?>

                        <div class="footer__subs__input">
							<?= $form->field($model, 'email', ['template' => '{input}'])->textInput([
								'placeholder' => 'Ваш e-mail',
							]) ?>
                        </div>

                        <div class="footer__subs__btn">
                            <div class="btn btn_fw btn_blue btn-send-handler"><?= Yii::t('app', 'Subscribe') ?></div>
                        </div>

                        <div class="hidden">
							<?= Html::submitButton('Подписаться', ['class' => 'btn btn_fw btn_blue']) ?>
                        </div>

						<?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <div class="footer__col">
                <div class="footer__list">
                    <div class="footer__list__title"><span><?= Yii::t('app', 'Products')?></span></div>
                    <ul>
						<?php foreach ($categories as $category) : ?>
                            <li>
                                <a href="<?= Url::to(['catalog/category', 'category' => $category->slug]) ?>"><?= $category->name ?></a>
                            </li>
						<?php endforeach ?>
                    </ul>
                </div>
            </div>
            <div class="footer__col">
                <div class="footer__list">
                    <div class="footer__list__title"><span><?= Yii::t('app', 'Downloads')?></span></div>
                    <ul>
						<?php foreach ($documents as $document) : ?>
                            <li>
                                <a href="<?= "/cdn/documents/{$document->id}/{$document->file}" ?>" target="_blank"><?= $document->name ?></a>
                            </li>
						<?php endforeach ?>
                    </ul>
                </div>
            </div>
            <div class="footer__col">
                <div class="footer__list">
                    <div class="footer__list__title"><span><?= Yii::t('app', 'Last events')?></span></div>
					<?php foreach ($events as $event) : ?>
                        <ul>
                            <li>
                                <a href="<?= Url::to(['event/view', 'url' => $event->slug]) ?>"><?= $event->name ?></a>
                            </li>
                        </ul>
					<?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</footer>
