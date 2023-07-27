<?php

use yii\db\Migration;

/**
 * Class m171113_104829_product_item_has_feature_value
 */
class m171113_104829_product_item_has_feature_value extends \noIT\feature\migrations\migrate_single_feature_junction {
    protected $sourceModelName = 'common\models\ProductFeatureValue';
    protected $entityModelName = 'common\models\ProductItem';
    protected $junctionTableName = 'product_item_has_pr_feature_value';
}
