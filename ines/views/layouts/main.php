<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use ines\assets\AppAsset;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
	<?= Html::csrfMetaTags() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<?= $this->render('_components/_open-graph-protocol') ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<img src="https://ines.ua/og-image.jpg" alt="Ines" style="display: none">
<div class="main-wrapper">
	<?= $this->render('_header') ?>
    <div class="main-content">
		<?= $content ?>
    </div>
	<?= $this->render('_footer') ?>
</div>

<div id="checkout" tabindex="-1" class="checkout modal fade"></div>
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls"><div class="slides"></div><a class="prev"></a><a class="next"></a><a class="close">×</a></div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
