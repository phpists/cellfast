<?php

use backend\widgets\MetronicBreadCrumbs;
use yii\helpers\Html;
use backend\widgets\MetronicAlert;

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
    <link rel="shortcut icon" href="/admin/metronic-admin/demo/default/media/img/logo/favicon.ico">
</head>

<?php $this->beginBody() ?>
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">

	<!-- BEGIN: Header -->
	<?= $this->render('header') ?>
	<!-- END: Header -->

	<!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

        <?= $this->render('sidebar') ?>

        <!--- --->
        <div class="m-grid__item m-grid__item--fluid m-wrapper">

		    <?= MetronicBreadCrumbs::widget([
			    'title' => $this->title,
			    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		    ])?>

            <?= MetronicAlert::widget() ?>

            <div class="m-content">
                <div class="row">
                    <div class="col-xl-12">
                    <?= $content ?>
                    </div>
                </div>
            </div>

        </div>
        <!--- --->

    </div>
	<!-- end:: Body -->

	<!-- begin::Footer -->
	<?= $this->render('footer') ?>
    <!-- end::Footer -->

</div>
<!-- end:: Page -->

<!-- begin::Scroll Top -->
<div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
</div>
<!-- end::Scroll Top -->

<!-- JS CODE START -->
<?php $this->endBody() ?>
<!-- JS CODE END -->
</body>
</html>
<?php $this->endPage() ?>