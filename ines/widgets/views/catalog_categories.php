<?php

use yii\helpers\Url;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $items \common\models\Category[] */
?>
<section class="mcnt__block mcatalog">
    <div class="mcnt__block__title"><span><span><?= Yii::t('app', 'Catalog')?></span></span></div>
    <div class="mcatalog__row">
	    <?= $this->render('/catalog/_categories', ['items' => $items])?>
    </div>
</section>