<?php

/* @var $this yii\web\View */
/* @var $points array */
/* @var $onlinePartnersModel \common\models\Partner */
/* @var $partner \common\models\Partner */
/* @var $locationRegion \common\models\Partner */
/* @var $offlinePartnersModel \common\models\Partner */

use yii\helpers\Html;
use yii\web\View;

$this->title = 'Где купить продукцию INES?';

$this->params['breadcrumbs'][] = Yii::t('app', 'Where can I buy');

\ines\assets\PartnersAsset::register($this);

?>
<section class="buy">
    <div class="container">

		<?= \ines\widgets\Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>

        <div class="buy__title"><span>Где купить</span></div>

        <!-- -->
		<?php if(!empty($onlinePartnersModel)) : ?>
            <section class="online-stores">
                <div class="online-stores-container">
                    <ul class="online-stores-list">
						<?php foreach($onlinePartnersModel as $partner) : ?>
                            <li class="online-stores-list-item">
                                <a href="<?= $partner->website ?>" target="_blank" class="online-stores-list-item-link">
									<?php if(!empty($partner->logotype)) : ?>
                                        <div class="online-stores-list-item__logo">
											<?= Html::img($partner->getThumbUploadUrl('logotype', 'thumb'), [
												'alt' => "Логотип {$partner->name}",
												'class' => 'u-object-fit-cover',
											]) ?>
                                        </div>
									<?php endif ?>
                                    <div class="online-stores-list-item-info">
                                        <div class="online-stores-list-item-info__name">
                                            <p><?= $partner->name ?></p>
                                        </div>
                                        <div class="online-stores-list-item-info__text">
                                            <p><?= $partner->caption ?></p>
                                        </div>
                                    </div>
                                </a>
                            </li>
						<?php endforeach ?>
                    </ul>
                </div>
            </section>
            <!-- -->
		<?php endif ?>

        <!-- -->
		<?php if(!empty($locationRegionModel)) : ?>
            <div class="buy__cnt">
                <div class="buy-content">
                    <div class="buy-map js-buy-map"></div>
                    <div class="buy-navigation">
                        <div id="buy-navigation-content"  class="buy-navigation-content js-buy-navigation-content">
                            <div class="buy-navigation-content__inner">

                                <!-- -->
								<?php foreach($locationRegionModel as $locationRegion) : ?>

									<?php if(!empty($locationRegion->partner)) : ?>

                                        <div data-state="close" class="buy-navigation-item js-buy-navigation-item">
                                            <a href="#" class="buy-navigation-item__link js-buy-navigation-item__link"><?= $locationRegion->name_uk_ua ?>
                                                <svg class="svg-icon angle-icon">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#angle-icon"></use>
                                                </svg>
                                            </a>
                                            <div class="buy-navigation-item__content js-buy-navigation-item__content">

                                                <ul class="buy-navigation-item-list">

													<?php foreach($locationRegion->partner as $partner) : ?>

                                                        <li class="buy-navigation-item-list__item js-buy-navigation-item-list__item">

															<?php

															$dataItemLocation = !empty($partner->coordinate) ? "data-item-location='$partner->coordinate'" : '';

															?>

                                                            <a href="#" <?= $dataItemLocation ?>
                                                               data-item-zoom="16"
                                                               class="buy-navigation-item-list__item-name js-buy-navigation-item-list__item-name"><?= $partner['name'] ?></a>

                                                            <p><?= $partner->address ?></p>

															<?php if(!empty($partner->phones) && !empty($partner->phones[0]['name'])) : ?>

                                                                <p>Телефон:
																	<?php foreach($partner->phones as $phone) : ?>
																		<?= $phone['name'] ?>
                                                                        <br>
																	<?php endforeach ?>
                                                                </p>

															<?php endif ?>

															<?php if(!empty($partner->email)) : ?>
                                                                <p>Email: <?= $partner->email ?></p>
															<?php endif ?>

                                                        </li>

													<?php endforeach ?>
                                                </ul>

                                            </div>
                                        </div>

									<?php else : ?>

										<?php continue ?>

									<?php endif ?>

								<?php endforeach ?>
                                <!-- -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
		<?php endif ?>

    </div>
</section>
