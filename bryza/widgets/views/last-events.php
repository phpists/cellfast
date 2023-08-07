<?php

use noIT\core\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;

/** @var $this \yii\web\View */
/** @var $title string */
/** @var $items \bryza\models\Event[] */

?>
<section class="mcnt__block mnews">
    <div class="mcnt__block__title"><span><?= $title ?></span></div>
    <div class="mnews__row row">
		<?php foreach ($items as $item) : ?>
            <div class="col-md-4 col-sm-6">
                <div class="mnews__it">
                    <a href="<?= Url::to(["event/view", 'url' => $item->slug]) ?>" class="mnews__it__img objectfit">
						<?= Html::img($item->getThumbUploadUrl('image', 'thumb_book')) ?>
                    </a>
                    <div class="mnews__it__date"></div>
                    <div class="mnews__it__title"><?= Html::a($item->name, ["event/view", 'url' => $item->slug]) ?></div>
                    <div class="mnews__it__txt"><span><?= StringHelper::truncate($item->body, 100, '', null, true) ?></span></div>
                </div>
            </div>
		<?php endforeach ?>
    </div>
</section>