<?php

use cellfast\widgets\FeatureTableWidget;
use noIT\imagecache\helpers\ImagecacheHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/*** Контейнер вывода карточки товара. */

/* @var $this yii\web\View */
/* @var $model \common\models\ProductEntity */
/* @var $imagesArray array */
/* @var $featureIds int[]|null */

\cellfast\assets\ProductAsset::register($this);

$__params = require __DIR__ . '/__params.php';

/** TODO - SEO title and meta-tags */
$this->title = $model->name;

$this->params['breadcrumbs'][] = [
	'label' => $__params['items'],
	'url'   => [ 'catalog/index' ],
];

foreach ( $model->category->parents as $parent ) {
	$this->params['breadcrumbs'][] = [ 'label' => $parent->name, 'url' => Url::to( [ 'catalog/category', 'category' => $parent ] ) ];
}
//$this->params['breadcrumbs'][] = ['label' => $model->category->name, 'url' => Url::to(['catalog/category', 'category' => $model->category])];
$this->params['breadcrumbs'][] = ['label' => $model->category->name, 'url' => Url::to(['catalog/category', 'category' => $model->category->slug ])];
$this->params['breadcrumbs'][] = $model->name;


/** Скрипт локального переключения комбинаций твоаров по характеристикам */
$featureMap = json_encode($model->definedFeaturesMap);

/** TODO - Реализовать логику представления доступности ключевых характеристик */
$itemNotFoundMessage = Yii::t('app', 'Product item not found');
$js = <<<JS
document.itemNotFoundMessage = "{$itemNotFoundMessage}.";
document.featuresMap = {$featureMap};
JS;
$this->registerJs($js, 4); // POS_READY

$featuresTable = FeatureTableWidget::widget( ['model' => $model] );

?>

<section id="product_<?= $model->id ?>" class="product <?= $__params['id'] ?>-view" data-ajaxurl="<?= Url::to( ['catalog/product-item', 'product' => $model ]) ?>">

    <div class="container">

		<?= \cellfast\widgets\Breadcrumbs::widget( [
			'links' => isset( $this->params['breadcrumbs'] ) ? $this->params['breadcrumbs'] : [],
		] ) ?>

		<?= \cellfast\widgets\Alert::widget() ?>

        <div class="product__title">
            <h1><?= $model->name ?></h1>
        </div>

        <div class="product__top <?= $__params['id'] ?>-view-wrapper">
            <div class="product__top__left">
                <div class="product__sliders">
                    <div class="product__slider__big-wrap">
                        <div class="product__slider__big">
							<?php // TODO замена изображения через смену комбинаций, как это реализовать при учете того что у нас Слайдер? ?>
							<?php if ( ( $image = $model->image ) ) : ?>
                                <div class="product__slider__big__slide">
                                    <a href="<?= ImagecacheHelper::getImageSrc( $model->image, 'fullsize' ) ?>">
										<?= ImagecacheHelper::getImage( $model->image, 'fullsize' ) ?>
                                    </a>
                                </div>
							<?php endif ?>
							<?php if ( $imagesArray) : ?>
								<?php foreach ( $imagesArray as $single_image ) : ?>
                                    <div class="product__slider__big__slide">
                                        <a href="<?= ImagecacheHelper::getImageSrc( $single_image['original'], 'fullsize' ) ?>">
											<?= ImagecacheHelper::getImage( $single_image['original'], 'fullsize' ) ?>
                                        </a>
                                    </div>
								<?php endforeach ?>
							<?php endif ?>
                        </div>
                    </div>
                    <!-- -->
                    <div class="product__slider__nav-wrap">
                        <div class="product__slider__nav">
							<?php if ( ( $image = $model->image ) ) : ?>
                                <div class="product__slider__nav__slide">
                                    <div class="product__slider__nav__slide__img objectfit">
										<?= ImagecacheHelper::getImage( $image, 'product_list_cover', [ 'class' => 'cover' ] ) ?>
                                    </div>
                                </div>
							<?php endif ?>

                            <?php if ($imagesArray) : ?>

								<?php foreach ( $imagesArray as $single_image ) : ?>

                                    <div class="product__slider__nav__slide">
                                        <div class="product__slider__nav__slide__img objectfit">
											<?= ImagecacheHelper::getImage( $single_image['thumb'], 'product_list_cover', [ 'class' => 'cover' ] ) ?>
                                        </div>
                                    </div>
								<?php endforeach ?>

							<?php endif ?>
                        </div>
                    </div>
                    <!-- -->
                </div>
            </div>

            <div class="product__top__right-wrap">
				<?php
				// Calc count of options for detect infocart-template
				$options_count = empty($model->definedFeatures) ? 0 : count( $model->definedFeatures );
				if ( $options_count > 3 ) {
					$options_count = 3;
				}
				?>
				<?= $this->render( "cards/infocard_{$options_count}", [
					'model' => $model,
					'features' => $model->definedFeatures,
                    'featureIds' => $featureIds,
				] ) ?>
            </div>
        </div>

        <div class="product__info">
            <div class="product__info__nav">
                <ul>
					<?php if ($model->body) : ?>
                        <li class="active"><a href="tab1"><span><?= Yii::t( 'app', 'Description' ) ?></span></a></li>
					<?php endif ?>
					<?php if ( $featuresTable ) : ?>
                        <li><a href="tab2"><span><?= Yii::t( 'app', 'Specifications' ) ?></span></a></li>
					<?php endif ?>
					<?php // TODO реализовать отзывы и их вывод ?>
					<?php if ( false ) : ?>
                        <li><a href="tab3"><span><?= Yii::t( 'app', 'Reviews' ) ?></span></a></li>
					<?php endif ?>
                </ul>
            </div>
            <div class="product__info__tabs">
				<?php if ($model->body) : ?>
                    <div data-tab="tab1" class="product__info__tab active">
                        <div class="product__info__cnt">
                            <div class="product__info__cnt__txt">
								<?= \noIT\core\helpers\Html::replaceImages($model->body, '@cellfast/views/site/_lightbox', ['src', 'width', 'height', 'alt', 'title', 'style']) ?>
                            </div>
							<?php /*
<!--                        <div class="product__info__cnt__more"><a href="#">Подробнее</a></div>-->
                        */ ?>
                        </div>
                    </div>
				<?php endif ?>
				<?php if ( $featuresTable ) : ?>
                    <div data-tab="tab2" class="product__info__tab <?= $__params['id'] ?>-features">
                        <div class="product__info__cnt">
							<?= \cellfast\widgets\FeatureTableWidget::widget( [
								'model' => $model,
							] );
							?>
                        </div>
                    </div>
				<?php endif ?>
				<?php if ( false ) : ?>
                    <div data-tab="tab3" class="product__info__tab">
                        <div class="product__info__cnt">
                            <div class="product__info__rews">
                                <div class="product__info__rews__slider">
                                    <div class="product__info__rews__slide">
                                        <div class="product__info__rews__cnt">
                                            <div class="product__info__rews__date"><span>22.07.2017</span></div>
                                            <div class="product__info__rews__name"><span>Василий Пупкин</span></div>
                                            <div class="product__info__rews__txt">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product__info__rews__slide">
                                        <div class="product__info__rews__cnt">
                                            <div class="product__info__rews__date"><span>22.07.2017</span></div>
                                            <div class="product__info__rews__name"><span>Василий Пупкин</span></div>
                                            <div class="product__info__rews__txt">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product__info__rews__slide">
                                        <div class="product__info__rews__cnt">
                                            <div class="product__info__rews__date"><span>22.07.2017</span></div>
                                            <div class="product__info__rews__name"><span>Василий Пупкин</span></div>
                                            <div class="product__info__rews__txt">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product__info__rews__slide">
                                        <div class="product__info__rews__cnt">
                                            <div class="product__info__rews__date"><span>22.07.2017</span></div>
                                            <div class="product__info__rews__name"><span>Василий Пупкин</span></div>
                                            <div class="product__info__rews__txt">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis, minus illum ipsam itaque dolor repudiandae, nihil laborum, sapiente, pariatur amet vero sequi in. Consequatur dicta veniam quisquam dolorem accusamus est.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product__info__rews__btns"><a href="#" class="product__info__rews__btn _prev">
                                        <svg class="svg-icon arrleft-icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#arrleft-icon"></use>
                                        </svg>
                                    </a><a href="#" class="product__info__rews__btn _next">
                                        <svg class="svg-icon arrright-icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#arrright-icon"></use>
                                        </svg>
                                    </a></div>
                            </div>
                        </div>
                    </div>
				<?php endif?>
            </div>
        </div>

    </div>

</section>

<?php /** TODO реализовать в шаблоне список комбинаций? */ ?>
<?php //= $this->render('/catalog/product/calculator/select_list', ['model' => $model]);?>
<?php //= $this->render('/catalog/product/calculator/modal', ['model' => $model]);?>
