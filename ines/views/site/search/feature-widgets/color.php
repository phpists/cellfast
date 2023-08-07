<?php

use noIT\core\helpers\Url;
use noIT\core\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\ProductFeature */
?>

<div class="ctlg__fltr__bl">
    <div class="ctlg__fltr__bl__title"><span><?php echo $model['group']->name; ?></span></div>
    <div class="ctlg__fltr__bl__colors">

	    <?php foreach ($model['values'] as $value) :?>
            <div class="ctlg__fltr__bl__colors__it<?= (empty($value->passitive) ? '' : ' passive')?>">
                <?= Html::checkbox("filter[{$value->id}]", array_key_exists($value->id, $values), ['hidden'=>true])?>
                <a class="_label filter_goselect" href="<?= (empty($value->passive) ? Url::to(Yii::$app->productFeature->toggleParams(['features' => [$value]]), false, false) : '#'); ?>">
                    <span style="background: <?php echo $value->value; ?>;" class="_icon">
                        <svg class="svg-icon check-icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/template/svg-sprite.svg#check-icon"></use>
                        </svg>
                    </span>
                    <span class="_text"><?= $value->value_label; ?></span>
                </a>
            </div>
	    <?php endforeach?>
    </div>
</div>
