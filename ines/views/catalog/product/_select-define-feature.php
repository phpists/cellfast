<?php

use noIT\core\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $product \common\models\ProductFeature */
/* @var $feature \common\models\ProductFeature */

$values = $feature->values;
?>
<?php if (count($values) > 2) :?>
	<?php // Тут будет вывод в таком виде http://joxi.ru/VrwMBZ6FO904R2 ?>
	<?= Html::dropDownList('', null, ArrayHelper::map($values, 'id', 'value_label'))?>
<?php else :?>
    <?php // Тут будет вывод в таком виде http://joxi.ru/Rmzz03Lh0B3M7m ?>
    <?= Html::dropDownList('', null, ArrayHelper::map($values, 'id', 'value_label'))?>
<?php endif?>
