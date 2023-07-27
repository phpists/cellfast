<?php
/** @var $this \yii\web\View */
/** @var $items \noIT\feature\models\Feature[] */
/** @var array $options */
/** @var array $itemOptions */
/** @var string $viewItem */

?>
<?php foreach($items as $item) :?>
    <?= $this->render($viewItem, [
        'route' => $item['route'],
        'item' => $item['item'],
        'label' => $item['label'],
        'options' => $itemOptions,
    ])?>
<?php endforeach?>
