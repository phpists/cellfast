<?php
/**
 * @var $data array
 * @var $imageAttributes array
 */

use backend\assets\LightBoxAsset;
use yii\helpers\Html;

LightBoxAsset::register($this);

if(empty($data['alt'])) {
	$alt = '';
} else {
	$alt = Html::encode($data['alt']);
}

?>
<a href="<?= $data['src'] ?>"
   data-lightbox="image"
	<?= empty($data['title']) ? 'data-title="' . $alt . '"' : 'data-title="' . Html::encode($data['title']) . '"' ?>
	<?= empty($data['style']) ? '' : 'style="' . $data['style'] . '"' ?>
>
	<?= Html::img($data['src'], [
		'width' => empty($data['width']) ? null : $data['width'],
		'height' => empty($data['height']) ? null : $data['height'],
	]) ?>
</a>