<?php

use yii\web\View;

/* @var $this yii\web\View */

\bryza\assets\MainAsset::register($this);

// TODO - Seo-tags
$this->title = Yii::$app->name;

?>
<?= \bryza\widgets\BannerWidget::widget() ?>

<section class="mcnt">
    <div class="container">

		<?= \bryza\widgets\CatalogWidget::widget()?>

		<?= \bryza\widgets\EventsWidget::widget()?>

		<?= \bryza\widgets\CertificatesWidget::widget()?>

		<?= \bryza\widgets\AboutUsWidget::widget()?>

    </div>
</section>
