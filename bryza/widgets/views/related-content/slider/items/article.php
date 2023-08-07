<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use noIT\imagecache\helpers\ImagecacheHelper;

/** @var $params array */
/** @var $model \yii\db\ActiveRecord */

?>
<div class="slider__slide">
    <div class="slider__slide__body">
		<?php if ( ($image = $model->image) ) : ?>
			<?= Html::a(
				Html::img($model->getThumbUploadUrl('image', 'thumb_book')) . '<svg class="svg-icon search-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#search-icon"></use></svg>',
				['article/view', 'url' => $model->slug],
				['class' => 'slider__slide__img']
			) ?>
		<?php endif ?>
        <div class="slider__slide__name"><?= Html::a( strip_tags($model->title), ['article/view', 'url' => $model->slug] ) ?></div>
        <div class="slider__slide__txt"><?= StringHelper::truncate(strip_tags($model->teaser), 88, '...',  null,  false) ?></div>
    </div>
</div>
