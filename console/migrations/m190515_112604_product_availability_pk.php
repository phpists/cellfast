<?php

use yii\db\Migration;

/**
 * Class m190515_112604_product_availability_pk
 */
class m190515_112604_product_availability_pk extends Migration
{
    public $modelProductAvailabilityName = 'common\models\ProductAvailability';

    public function up()
    {
        $this->addPrimaryKey('product_availability_pk', ($this->modelProductAvailabilityName)::tableName(), ['product_item_id', 'warehouse_id']);
    }

    public function down()
    {
        $this->dropPrimaryKey('product_availability_pk', ($this->modelProductAvailabilityName)::tableName());

        return true;
    }
}
