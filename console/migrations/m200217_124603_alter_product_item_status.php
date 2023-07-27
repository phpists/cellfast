<?php

use yii\db\Migration;

/**
 * Class m200217_124603_alter_product_item_status
 */
class m200217_124603_alter_product_item_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product_item', 'status', $this->tinyInteger(2)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product_item', 'status');

        return true;
    }
}
