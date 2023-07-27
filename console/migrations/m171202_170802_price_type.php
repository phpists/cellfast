<?php

use yii\db\Migration;
use common\helpers\AdminHelper;

/**
 * Class m171202_170802_price_type
 */
class m171202_170802_price_type extends Migration
{
	protected $modelTypeName = 'common\models\PriceType';
	protected $modelProductPriceName = 'common\models\ProductPrice';
	protected $modelProductItemName = 'common\models\ProductItem';

	protected function columnsType() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'native_name' => $this->string(150),
				'includeVAT' => $this->boolean()->defaultValue(false),
			],
			AdminHelper::migrateTableLangs('name', $this->string(150)),
			AdminHelper::migrateTableSort($this),
			AdminHelper::migrateTableTS($this)
		);
	}

	protected function columnsProductPrice() {
		return array_merge(
			[
				'product_item_id' => $this->integer()->notNull(),
				'price_type_id' => $this->integer()->notNull(),
				'price' => $this->float(3),
				'common_price' => $this->float(3),
			],
			AdminHelper::migrateTableTS($this)
		);
	}

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    $this->createTable(($this->modelTypeName)::tableName(), $this->columnsType(), AdminHelper::migrateTableOptions($this->db->driverName));

	    $this->createTable(($this->modelProductPriceName)::tableName(), $this->columnsProductPrice(), AdminHelper::migrateTableOptions($this->db->driverName));

	    $this->addPrimaryKey('price_type_pk', ($this->modelProductPriceName)::tableName(), ['product_item_id', 'price_type_id']);

	    $this->addForeignKey('product_price_type_fk', ($this->modelProductPriceName)::tableName(), 'price_type_id', ($this->modelTypeName)::tableName(), 'id', 'CASCADE', 'CASCADE');
	    $this->addForeignKey('product_price_product_item_fk', ($this->modelProductPriceName)::tableName(), 'product_item_id', ($this->modelProductItemName)::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
	    $this->dropTable(($this->modelProductPriceName)::tableName());
	    $this->dropTable(($this->modelTypeName)::tableName());
    }
}
