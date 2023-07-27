<?php

/** @var $this \yii\web\View */
/** @var $component \common\components\GdsCalc\components\GdsCalcDrain */

?>

<?php foreach ($component->models as $model) :?>
    <a href="#" class="<?= $model->alias?>" data-alias="<?= $model->alias?>"><span>&nbsp;</span></a>
<?php endforeach;?>
