<?php

/** @var $this \yii\web\View */
/** @var $items array */
/** @var $language \common\components\language\Language */
?>
<?php foreach ($items as $item) :?>
<a href="#" data-env="dev" <?= ($item['active'] ? 'class="active" onclick="return false;"' : 'onclick="window.location.href=\''. \yii\helpers\Url::to($item['url']) .'\'"')?>><?= $item['label']?></a>
<?php endforeach?>
