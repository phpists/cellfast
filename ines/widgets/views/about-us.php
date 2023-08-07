<?php

use yii\helpers\Html;

?>
<section class="maboutw">

    <div class="mabout">
        <h1 class="mabout__title"><span style="font-weight: 700;"><?= $model->name ?></span></h1>
        <div class="mabout__wrap">

			<?php if($model->cover) : ?>
                <div class="mabout__cnt__img">
					<?= Html::img($model->getThumbUploadUrl('cover', 'thumb'), [
						'alt' => $model->name .' - CELLFAST',
					]) ?>
                    <br>
                </div>
			<?php endif ?>

			<?php if($model->info_image_1 && $model->infoTeaser_1) : ?>
                <div class="mabout__row">
                    <div class="mabout__img objectfit company-logo">
						<?= Html::img($model->getThumbUploadUrl('info_image_1', 'thumb'), [
							'alt' => $model->name .' - 1',
						]) ?>
                    </div>
                    <div class="mabout__txt">
                        <div class="mabout__txt__inn">
							<?= $model->infoTeaser_1 ?>
                        </div>
                    </div>
                </div>
			<?php endif ?>

			<?php if($model->info_image_2 && $model->infoTeaser_2) : ?>
                <div class="mabout__row">
                    <div class="mabout__img objectfit company-logo">
						<?= Html::img($model->getThumbUploadUrl('info_image_2', 'thumb'), [
							'alt' => $model->name .' - 2',
						]) ?>
                    </div>
                    <div class="mabout__txt">
                        <div class="mabout__txt__inn">
							<?= $model->infoTeaser_2 ?>
                        </div>
                    </div>
                </div>
			<?php endif ?>

        </div>
    </div>

	<?php if($model->body) : ?>
        <div class="container">
            <div class="mabout" style="margin-top: 10px">
                <div>
                    <div class="mabout__txt__inn">
						<?= $model->body ?>
                    </div>
                </div>
            </div>
        </div>
	<?php endif ?>

</section>