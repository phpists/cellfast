<?php
/** @var array $groups */
/** @var array $params */

use yii\helpers\Html;
use common\helpers\ProductHelper;

$items = [];
foreach($groups as $group) {
    foreach($group->_options as $option) {
        $url = ProductHelper::catalogUrl(ProductHelper::toggleParams($params, ['options' => $option]));
        $active = (!empty($params['options']) && array_key_exists($option['id'], $params['options']));
        $items[] = Html::a($option['value'], $url, ['class' => $active ? 'active' : '']);
    }
}
?>
<?= implode(', ', $items)?>