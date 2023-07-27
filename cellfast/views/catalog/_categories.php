<?php

use yii\helpers\Url;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $items \common\models\Category[] */
?>
<?php foreach ($items as $category) : ?>
    <?= $this->render('_category', ['category' => $category]) ?>
<?php endforeach ?>