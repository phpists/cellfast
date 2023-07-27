<?php

/**
 * @var $this \yii\web\View
 * @var $content string
 */

use yii\helpers\Html;
use yii\helpers\Url;

backend\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title><?= Html::encode($this->title) ?></title>
		<?= Html::csrfMetaTags() ?>
		<?php $this->head() ?>
        <link rel="shortcut icon" href="<?= Url::to('favicon.ico')?>">
    </head>

	<?php $this->beginBody() ?>
    <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--signin login-background" id="m_login">
            <div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
                <div class="m-stack m-stack--hor m-stack--desktop">
                    <div class="m-stack__item m-stack__item--fluid">
                        <div class="m-login__wrapper">
							<?= $content ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>

		<?php

		?>
        <!-- JS CODE START -->
		<?php $this->endBody() ?>
        <!-- JS CODE END -->
    </body>
    </html>
<?php $this->endPage() ?>