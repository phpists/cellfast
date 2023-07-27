<?php

use yii\helpers\Html;
use cellfast\widgets\RelatedContentWidget;
use noIT\imagecache\helpers\ImagecacheHelper;
use yii\web\View;

\cellfast\assets\MainAsset::register($this);
\cellfast\assets\ContentAsset::register($this);

/* @var $this yii\web\View */
/* @var $model \common\models\Article */

/** TODO - SEO title and meta-tags */
$this->title = $model->name;

$this->params['breadcrumbs'][] = [
	'label' => $__params['items'],
	'url' => ["{$__params['id']}/index"],
];

$this->params['breadcrumbs'][] = $model->name;

?>
<div class="main-content">
    <section class="content <?= $__params['id'] ?>-view">
        <div class="container">
			<?= \cellfast\widgets\Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				'options' => ['class'=>'']
			]) ?>

			<?= \cellfast\widgets\Alert::widget() ?>

            <div class="content__body <?= $__params['id'] ?>-view-wrapper">
				<?php if ( ($image = $model->image_list) ) : ?>
                    <div class="content__topimg">
						<?= Html::a(
							ImagecacheHelper::getImage($model->getUploadUrl('image'), 'content_cover', ['class' => '']),
							["{$__params['id']}/view", 'url' => $model->slug],
							['class' => 'content__items__img']
						); ?>
                    </div>
				<?php endif ?>
                <div class="content__date <?= $__params['id'] ?>-published">
                    <span><?= Yii::$app->formatter->asDate($model->published_at) ?></span>
                </div>
                <div class="content__title <?= $__params['id'] ?>-title">
                    <h1><?= $model->name ?></h1>
                </div>
                <div class="content__txt <?= $__params['id'] ?>-body">
                    <?= \noIT\core\helpers\Html::replaceImages($model->body, '@cellfast/views/site/_lightbox', ['src', 'width', 'height', 'alt', 'title', 'style']) ?>
                </div>
				<?php /** Print entity tags if exists */ ?>
				<?php /* TODO - рефактор виджета тегов
                if ( $model->tags ) :?>
                    <div class="content__tags">
                        <div class="<?= $__params['id']?>-tags content-tags">
                            <?= \noIT\feature\widgets\FeatureWidget::widget([
                                'items' => $model->tags,
                                'routeBase' => ["{$__params['id']}/index"],
                                'routeFilterKey' => 'tag',
                                'itemAttributeValue' => 'slug',
                                'options' => [],
                            ])?>
                        </div>
                    </div>
                <?php endif */ ?>
            </div>

			<?php if ( $model->images ) : ?>
                <div class="<?= $__params['id'] ?>-images">
					<?= \cellfast\widgets\ImageGallery::widget([
						'items' => $model->images,
					])?>
                </div>
			<?php endif ?>

        </div>
    </section>

	<?= RelatedContentWidget::widget([
		'title' => 'Другие товары',
		'items' => \common\models\Product::find()->where(['project_id' => Yii::$app->projects->current->alias])->limit(4)->orderBy(new \yii\db\Expression('rand()'))->all(),
		'view' => 'related-content/product/index',
		'viewItem' => 'items/product',
	]) ?>

	<?= RelatedContentWidget::widget([
		'title' => 'Другие статьи',
		'items' => \cellfast\models\Article::find()->where(['project_id' => Yii::$app->projects->current->alias])->limit(3)->orderBy(['published_at' => SORT_DESC])->all(),
		'view' => 'related-content/static/index',
		'viewItem' => 'items/article',
	]) ?>

	<?= \cellfast\widgets\CertificatesWidget::widget()?>

	<?= \cellfast\widgets\EventsWidget::widget([
	        'bgColor' => 'background: #f4f4f4;margin-bottom: -60px;'
    ])?>

    <br>
    <br>
    <br>

</div>
