<?php

use yii\db\Migration;
use common\helpers\AdminHelper;

/**
 * Class m171112_150531_product_type_has_feature
 */
class m171112_150531_product_type_has_feature extends \noIT\junction\migrations\migrate_junction
{
    protected $sourceModelName = 'common\models\ProductType';
    protected $entityModelName = 'common\models\ProductFeature';
    protected $junctionTableName = 'product_type_has_product_feature';
    protected $junctionSourceField = 'product_type_id';
    protected $junctionEntityField = 'product_feature_id';

    protected function columnsJunction()
    {
        return array_merge(
            parent::columnsJunction(),
            [
                'defining' => $this->boolean()->defaultValue(false),
                /**
                 * Указывает возможность множественной привязки значений к целевой сущности (товару)
                 */
                'multiple' => $this->boolean()->defaultValue(false),
                'admin_widget' => $this->string('255'),
                'filter_widget' => $this->string('255'),
            ]
        );
    }
}
