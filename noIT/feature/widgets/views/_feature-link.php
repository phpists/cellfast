<?php
/** @var $this \yii\web\View */
/** @var $route array|string */
/** @var array $options */
/** @var string $label */

use yii\helpers\Html;
?>
<?= Html::a($label, \yii\helpers\Url::to($route), $options)?>