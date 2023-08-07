<?php

/* @var $this yii\web\View */
/* @var $model AboutUs */

$this->title = 'BRYZA';
$this->params['breadcrumbs'][] = Yii::t('app', 'About company');

$this->registerCssFile('css/main.css?31245', ['depends' => \bryza\assets\AppAsset::className()]);

use common\models\AboutUs;
use yii\helpers\Html;

?>
<section class="maboutw about-us-page">
    <div class="container">

        <?= \bryza\widgets\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <div class="mabout">

            <h1 class="mabout__title">
                <span><?= $model->name ?></span>
            </h1>

            <div class="mabout__wrap">
                <div class="mabout__row">

                    <?php if($model->cover) : ?>
                        <div class="mabout__img objectfit company-logo">
                            <?= Html::img($model->getThumbUploadUrl('cover', 'thumb'), [
                                'alt' => "Bryza",
                            ]) ?>
                        </div>
                    <?php endif ?>

                    <div class="mabout__txt">
                        <div class="mabout__txt__inn">
                            <?= $model->teaser ?>
                        </div>
                    </div>

                </div>

                <?php if($model->cover_2 && $model->teaser_2) : ?>
                    <div class="mabout__row">

                        <?php if($model->cover_2) : ?>
                            <div class="mabout__img objectfit company-logo">
                                    <?= Html::img($model->getThumbUploadUrl('cover_2', 'thumb'), [
                                        'alt' => Yii::t('app', "Логистический центр в с. Рогозов, Киевская обл."),
                                ]) ?>
                            </div>
                        <?php endif ?>

                        <div class="mabout__txt">
                            <div class="mabout__txt__inn">
                                <?= $model->teaser_2 ?>
                            </div>
                        </div>

                    </div>
                <?php endif ?>

            </div>

            <?php if($model->teaser_3) : ?>
                <div class="mabout__wrap">
                    <div class="mabout__row">

                        <div class="mabout__txt" style="width: auto">
                            <div class="mabout__txt__inn">
                                <?= $model->teaser_3 ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endif ?>

            <?php if($model->body) : ?>
                <div class="mabout__wrap">
                    <div class="mabout__row">

                        <div class="mabout__txt" style="width: auto">
                            <div class="mabout__txt__inn">
                                <?= $model->body ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endif ?>

            <?php if($model->video) : ?>
                <div class="mabout__wrap">
                    <iframe style="display: block; margin: 0 auto; width: 100%;" width="960" height="675" src="<?= $model->video ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            <?php endif ?>

            <?php if($model->body_2) : ?>
                <div class="mabout__wrap">
                    <div class="mabout__row">

                        <div class="mabout__txt" style="width: auto">
                            <div class="mabout__txt__inn">
                                <?= $model->body_2 ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endif ?>

        </div>

    </div>
</section>
