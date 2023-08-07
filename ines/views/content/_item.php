<?php

/* @var $this yii\web\View */
/* @var $model \ines\models\Article */
/* @var $__params string[] */

use yii\helpers\Html;
use yii\web\View;
use noIT\imagecache\helpers\ImagecacheHelper;

?>
<div class="content__items <?= $__params['id'] ?>-item">
	<?php if ( ($image = $model->image_list) ) : ?>
		<?= Html::a(
			Html::img($model->getThumbUploadUrl('image_list', 'thumb_book')),
			["{$__params['id']}/view", 'url' => $model->slug],
			['class' => 'content__items__img']
		) ?>
	<?php endif ?>
    <div class="content__items__cnt">
        <div class="content__items__date"><span><?php echo Yii::$app->formatter->asDatetime($model->published_at, 'dd/MM/yyyy') ?></span></div>
        <div class="content__items__title <?= $__params['id']?>-title"><?= Html::a($model->name, ["{$__params['id']}/view", 'url' => $model->slug]) ?></div>
        <div class="content__items__txt <?= $__params['id']?>-teaser"><?= $model->teaser ?></div>
        <div class="content__items__more"><?php echo Html::a( 'Читать полностью', ["{$__params['id']}/view", 'url' => $model->slug] ) ?></div>
    </div>
</div>
