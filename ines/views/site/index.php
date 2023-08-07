<?php

use yii\web\View;

/* @var $this yii\web\View */

\ines\assets\MainAsset::register($this);

// TODO - Seo-tags
$this->title = Yii::$app->name;

?>
<?= \ines\widgets\BannerWidget::widget() ?>

<section class="mcnt">
    <div class="container">

		<?= \ines\widgets\CatalogWidget::widget()?>

		<?= \ines\widgets\EventsWidget::widget()?>

		<?= \ines\widgets\CertificatesWidget::widget()?>

		<?= \ines\widgets\AboutUsWidget::widget()?>

    </div>
</section>
