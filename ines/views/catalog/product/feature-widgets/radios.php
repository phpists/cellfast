<?php

use noIT\core\helpers\Url;
use noIT\core\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\ProductFeature */
/* @var $values array */
?>
<div class="ctlg__fltr__bl__title"><span><?= $model['group']->name; ?></span></div>
<div class="ctlg__fltr__bl__radios">
	<?php foreach ($model['values'] as $value) :?>
		<div class="ctlg__fltr__bl__radios__it <?= ( isset($value->caption) && !empty($value->caption) ? '_w-accord' : ''); ?><?= (empty($value->passive) ? '' : ' passive')?>">
            <?= Html::radio("filter[{$value->id}]", array_key_exists($value->id, $values), [
                'class' => 'hidden-input'
            ]); ?>
            <a class="_label filter_goselect" href="<?= (empty($value->passive) ? Url::to(Yii::$app->productFeature->toggleParams(['features' => [$value]]), false, false) : '#'); ?>">
                <span class="_icon"></span>
                <span class="_text"><?= $value->value_label; ?></span>
			</a>
			<?php if( isset($value->caption) && !empty($value->caption)) {?>
                <div class="_w-accord__txt">
					<?= $value->caption; ?>
                </div>
			<?php } ?>
		</div>
	<?php endforeach; ?>
</div>
