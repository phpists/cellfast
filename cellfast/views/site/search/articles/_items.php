<?php

/**
 * Шаблон вывода списка элементов. Может использоваться для ajax подгрузки
 */

/* @var $this yii\web\View */
/* @var $model \cellfast\models\Article */
/* @var $__params string[] */

?>
<?php if (!$articles->count) : ?>
    <div class="empty-result">
        <?= $__params['empty']?>
    </div>
<?php else : ?>
    <?php foreach $articles as $i => $model) : ?>
        <?= $this->render('_item', [
            'model' => $model,
            'row_index' => $i,
            '__params' => $__params,
        ])?>
    <?php endforeach ?>
<?php endif ?>