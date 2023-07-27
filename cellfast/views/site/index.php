<?php

/* @var $this yii\web\View */


\cellfast\assets\MainAsset::register($this);

// TODO - Seo-tags
$this->title = Yii::$app->name;
?>

<?= \cellfast\widgets\BannerWidget::widget()?>

<?= \cellfast\widgets\CatalogWidget::widget()?>

<?= \cellfast\widgets\EventsWidget::widget()?>

<?= \cellfast\widgets\CertificatesWidget::widget()?>

<?= \cellfast\widgets\AboutUsWidget::widget()?>
