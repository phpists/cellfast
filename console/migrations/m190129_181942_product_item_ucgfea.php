<?php

use common\models\ProductItem;
use yii\db\Migration;

/**
 * Class m190129_181942_product_item_ucgfea
 */
class m190129_181942_product_item_ucgfea extends Migration
{
    public function up()
    {
        $this->addColumn(ProductItem::tableName(), 'ucgfea', $this->string(13));

        // todo ~May be unique
    }

    public function down()
    {
        $this->dropColumn(ProductItem::tableName(), 'ucgfea');

        return true;
    }
}
