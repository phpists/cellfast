<?php

use noIT\core\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;

/** @var $this \yii\web\View */
/** @var $bgColor string */
/** @var $title string */
/** @var $items \cellfast\models\Event[] */

?>
<section class="mnews" style="<?= $bgColor ?>">
    <div class="container">
        <div class="mnews__title"><span><?= $title ?></span></div>
        <div class="mnews__cnt">
			<?php foreach ($items as $item) : ?>
                <div class="mnews__cnt__col">
                    <div class="mnews__it">
                        <a href="<?= Url::to(["event/view", 'url' => $item->slug]) ?>" class="mnews__it__img objectfit">
							<?= Html::img($item->getThumbUploadUrl('image', 'thumb_book')) ?>
                        </a>
                        <div class="mnews__it__cnt">
                            <div class="mnews__it__title"><?= Html::a($item->name, ["event/view", 'url' => $item->slug]) ?></div>
                            <div class="mnews__it__txt"><span><?= StringHelper::truncate($item->body, 100, '', null, true) ?></span></div>
                        </div>
                    </div>
                </div>
			<?php endforeach ?>
        </div>
    </div>
</section>
