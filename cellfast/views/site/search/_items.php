<?php

/**
 * Шаблон вывода списка элементов. Может использоваться для ajax подгрузки
 */

/* @var $this yii\web\View */
/* @var $__params string[] */
/* @var $featureIds int[] */

?>
<?php foreach (array_values($dataProvider->models) as $i => $row) :?>
    <?php
    $entity = \common\models\ProductEntity::findOne($row);
//    $entity->item = isset($row['items']) && is_array($row['items']) && count($row['items']) == 1 ? \common\models\ProductItem::findOne($row['items'][0]) : $entity->getItem($featureIds);
    $entity->item = isset($row['items']) && is_array($row['items']) && count($row['items']) == 1 ? \common\models\ProductItem::findOne($row['items'][0]) : null;
    ?>
    <?= $this->render('_item', [
        'model' => $entity,
        'row_index' => $i,
        '__params' => $__params,
//        'featureIds' => $featureIds,
    ])?>
<?php endforeach?>
