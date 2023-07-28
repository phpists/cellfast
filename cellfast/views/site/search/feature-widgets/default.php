<?php

use noIT\core\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\ProductFeature */
?>
<?php foreach ($model['values'] as $value) :?>
    <div class="filter-option<?= (empty($value->passive) ? '' : ' passive')?>">
	    <?= Html::checkbox("filter[{$value->id}]", array_key_exists($value->id, $values))?>
        <?= Html::a($value->value_label, Yii::$app->productFeature->toggleParams(['features' => [$value]]), ['encode' => false])?>
        <?php // = Html::tag('div', $value->caption)?>
    </div>
<?php endforeach?>