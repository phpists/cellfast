<?php

use noIT\core\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\ProductFeature */
/* @var $values array */
?>
<div class="ctlg__fltr__bl__title"><span><?= $model['group']->name; ?></span></div>
<div class="ctlg__fltr__bl__catalog">
	<ul>
		<?php foreach ($model->values as $value) :?>
			<li class="<?= (array_key_exists($value->id, $values) ? 'active' : '')?><?= (empty($value->passive) ? '' : ' passive')?>">
                <?= Html::a($value->value_label, (empty($value->passive) ? Yii::$app->productFeature->toggleParams(['features' => [$value]]) : '#'), ['encode' => false])?>
            </li>
		<?php endforeach; ?>
	</ul>
</div>