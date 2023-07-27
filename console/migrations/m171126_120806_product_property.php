<?php

use yii\db\Migration;
use common\models\Product;
use common\helpers\AdminHelper;

/**
 * Class m171126_120806_product_property
 */
class m171126_120806_product_property extends Migration
{
	protected $modelName = 'common\models\ProductProperty';

	protected function columns() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'product_id' => $this->integer(),
			],
			AdminHelper::migrateTableLangs('name', $this->string(150)),
			AdminHelper::migrateTableLangs('value', $this->text()),
			AdminHelper::migrateTableSort($this),
			AdminHelper::migrateTableTS($this)
		);
	}

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    $this->createTable(($this->modelName)::tableName(), $this->columns(), AdminHelper::migrateTableOptions($this->db->driverName));

	    $this->addForeignKey('product_property_product_fk', ($this->modelName)::tableName(), 'product_id', Product::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
	    $this->dropTable(($this->modelName)::tableName());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171126_120806_product_property cannot be reverted.\n";

        return false;
    }
    */
}
