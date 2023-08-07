<?php

use yii\helpers\Html;
use bryza\widgets\RelatedContentWidget;
use noIT\imagecache\helpers\ImagecacheHelper;

\bryza\assets\MainAsset::register($this);
\bryza\assets\ContentAsset::register($this);

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

			<?= \bryza\widgets\Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				'options' => ['class'=>'']
			]) ?>

			<?= \bryza\widgets\Alert::widget() ?>

            <div class="content__body <?= $__params['id'] ?>-view-wrapper">
				<?php if ( ($image = $model->image_list) ) : ?>
                    <div class="content__topimg">
						<?= Html::a(
							ImagecacheHelper::getImage($model->getUploadUrl('image'), 'content_cover', ['class' => '']),
							["{$__params['id']}/view", 'url' => $model->slug],
							['class' => 'content__items__img']
						) ?>
                    </div>
				<?php endif ?>
                <div class="content__date <?= $__params['id'] ?>-published">
                    <span><?= Yii::$app->formatter->asDate($model->published_at) ?></span>
                </div>
                <div class="content__title <?= $__params['id'] ?>-title">
                    <h1><?= $model->name ?></h1>
                </div>
                <div class="content__txt <?= $__params['id'] ?>-body">
                    <?= \noIT\core\helpers\Html::replaceImages($model->body, '@bryza/views/site/_lightbox', ['src', 'width', 'height', 'alt', 'title', 'style']) ?>
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
                <div class="<?= $__params['id']?>-images">
					<?= \bryza\widgets\ImageGallery::widget([
						'items' => $model->images,
					])?>
                </div>
			<?php endif ?>

        </div>
    </section>

	<?= RelatedContentWidget::widget([
		'title' => 'Другие статьи',
		'items' => \bryza\models\Article::find()->where(['project_id' => Yii::$app->projects->current->alias])->limit(3)->orderBy(['published_at' => SORT_DESC])->all(),
		'view' => 'related-content/static/index',
		'viewItem' => 'items/article',
	]) ?>

	<?= RelatedContentWidget::widget([
		'title' => 'Другие статьи',
		'items' => \bryza\models\Article::find()->where(['project_id' => Yii::$app->projects->current->alias])->limit(3)->orderBy(['published_at' => SORT_DESC])->all(),
		'view' => 'related-content/static/index',
		'viewItem' => 'items/article',
	]) ?>

    <section class="mcnt" style="padding-bottom: 90px;background: #f4f4f4;">
        <div class="container">
	<?= \bryza\widgets\EventsWidget::widget([
	        'title' => Yii::t('app', 'Другие события'),
    ])?>
        </div>
    </section>

</div>
