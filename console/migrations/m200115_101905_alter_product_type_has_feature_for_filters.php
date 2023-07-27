<?php

use yii\db\Migration;

/**
 * Class m200115_101905_alter_product_type_has_feature_for_filters
 */
class m200115_101905_alter_product_type_has_feature_for_filters extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product_type_has_product_feature', 'in_filter', $this->boolean()->defaultValue(true));
        $this->addColumn('product_type_has_product_feature', 'sort_order', $this->integer(2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product_type_has_product_feature', 'in_filter');
        $this->dropColumn('product_type_has_product_feature', 'sort_order');

        return true;
    }
}
