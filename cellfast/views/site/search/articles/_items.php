<?php

/**
 * Шаблон вывода списка элементов. Может использоваться для ajax подгрузки
 */

/* @var $this yii\web\View */
/* @var $model \cellfast\models\Article */
/* @var $__params string[] */

?>
    <?php foreach ($articles as $i => $model) : ?>
        <?= $this->render('_item', [
            'model' => $model,
            'row_index' => $i,
            '__params' => $__params,
        ])?>
    <?php endforeach ?>
