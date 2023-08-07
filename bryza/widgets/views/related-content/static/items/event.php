<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use noIT\imagecache\helpers\ImagecacheHelper;

/** @var $params array */
/** @var $model \yii\db\ActiveRecord */

?>
<div class="related__content__item">
    <div class="static__infocard">
		<?php if ( ($image = $model->image) ) : ?>
			<?= Html::a(
				Html::img($model->getThumbUploadUrl('image', 'thumb_book')),
				['event/view', 'url' => $model->slug],
				['class' => 'static__infocard__image']
			) ?>
		<?php endif ?>
        <div class="static__infocard__body">
            <div class="static__infocard__title"><?= Html::a( StringHelper::truncate(strip_tags($model->name), 60), ['event/view', 'url' => $model->slug] ) ?></div>
            <div class="static__infocard__text"><?= StringHelper::truncate(strip_tags($model->teaser), 88, '...',  null,  false) ?></div>
        </div>
    </div>
</div>