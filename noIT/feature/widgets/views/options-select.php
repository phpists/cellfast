<?php
/** @var array $groups */
/** @var array $params */

use yii\helpers\Url;
use common\helpers\ProductHelper;

if (empty($params['options'])) {
    $params['options'] = [];
}

$_options = [];
foreach($params['options'] as $option) {
    $_options[$option->feature_id] = $option;
}

$all_params = $params;
?>
<?php if (!empty($groups)) :?>
    <?php foreach ($groups as $group) :?>
        <?php
        $_promptParams = $_options;
        if (isset($_promptParams[$group->id])) {
            unset($_promptParams[$group->id]);
        }
        $selected = '';
        $items = [];
        foreach($group->_options as $option) {
            $currenctParams = $_options;
            $currenctParams[$group->id] = $option;

            $all_params['options'] = array_values($currenctParams);

            $url = Url::to(ProductHelper::catalogUrl($all_params));
            $items[$url] = $option['value'];

            if (!empty($params['options']) && array_key_exists($option['id'], $params['options'])) {
                $selected = $url;
            }
        }
        if (count($items) > 1) {
            $all_params['options'] = array_values($_promptParams);
            $items = [Url::to(ProductHelper::catalogUrl($all_params)) => 'Все значения'] + $items;
        }
        ?>
        <?php if (count($items) > 1) :?>
            <div class="selectize_item">
                <label for="feature_<?= $group->id?>"><?= $group->name?></label>
                <?= \yii\helpers\Html::dropDownList('options['. $group->id .']', $selected, $items, ['id' => 'feature_'. $group->id])?>
            </div>
        <?php endif?>
    <?php endforeach?>
<?php endif?>