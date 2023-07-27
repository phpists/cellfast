<?php

use yii\db\Migration;

/**
 * Class m171112_171826_product_has_feature_value
 */
class m171112_171826_product_has_feature_value extends \noIT\feature\migrations\migrate_single_feature_junction {
    protected $sourceModelName = 'common\models\ProductFeatureValue';
    protected $entityModelName = 'common\models\Product';

}
