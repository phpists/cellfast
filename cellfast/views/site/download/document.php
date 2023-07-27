<?php

/**
 * @var $this \yii\web\View
 * @var $model \common\models\Document
 * @var $model \common\models\Document
 */

use yii\helpers\Url;
use yii\helpers\Html;
use cellfast\widgets\Breadcrumbs;

/** TODO - SEO title and meta-tags */
$this->title = Yii::t('app', 'Documents');

$this->params['breadcrumbs'][] = Yii::t('app', 'Documents');

$this->registerCssFile('css/events.css?315', [
	'depends' => 'cellfast\assets\AppAsset'
])

?>
<section class="events">
    <div class="container">

		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			'options' => [],
		]) ?>

        <div class="events__title">
            <span><?= $this->title ?></span>
        </div>

        <div class="events__cnt">
			<?php foreach ($dataProvider->getModels() as $model) : ?>
                <div class="events__it">

					<?php if(!empty($model->cover_image)) : ?>
                        <a href="<?= $model->getUploadUrl('file') ?>" class="events__it__img" target="_blank">
							<?= Html::img($model->getThumbUploadUrl('cover_image', 'thumb_book')) ?>
                        </a>
					<?php endif ?>

                    <div class="events__it__cnt">
                        <div class="events__it__title">
							<?= Html::a($model->name, $model->getUploadUrl('file'), [
								'target' => '_blank',
							]) ?>
                        </div>
                        <div class="events__it__txt">
                            <p><?= $model->caption ?></p>
                        </div>
                        <div class="events__it__more">
							<?= Html::a(Yii::t('app', 'Download'), $model->getUploadUrl('file'), [
								'target' => '_blank',
							]) ?>
                        </div>
                    </div>
                </div>
			<?php endforeach ?>
        </div>

        <div class="events__footer">
		    <?= \cellfast\widgets\PaginationWidget::widget([
			    'pagination' => $dataProvider->pagination,
		    ]) ?>
        </div>

    </div>
</section>
