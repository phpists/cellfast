<?php

use yii\db\Migration;
use common\helpers\AdminHelper;

/**
 * Class m171203_085750_warehouse
 */
class m171203_085750_warehouse extends Migration
{
	protected $modelWarehouseName = 'common\models\Warehouse';
	protected $modelProductAvailabilityName = 'common\models\ProductAvailability';
	protected $modelProductItemName = 'common\models\ProductItem';

	protected function columnsWarehouse() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'native_name' => $this->string(150),
				'location_country_id' => $this->integer(),
				'location_region_id' => $this->integer(),
				'location_place_id' => $this->integer(),
				'lng' => $this->float(7),
				'lat' => $this->float(7),
			],
			AdminHelper::migrateTableLangs('address', $this->string(150)),
			AdminHelper::migrateTableSort($this),
			AdminHelper::migrateTableTS($this)
		);
	}

	protected function columnsProductAvailability() {
		return array_merge(
			[
				'product_item_id' => $this->integer()->notNull(),
				'warehouse_id' => $this->integer()->notNull(),
				'status' => $this->smallInteger(2),
			],
			AdminHelper::migrateTableTS($this)
		);
	}

	/**
     * @inheritdoc
     */
    public function safeUp()
    {
	    $this->createTable(($this->modelWarehouseName)::tableName(), $this->columnsWarehouse(), AdminHelper::migrateTableOptions($this->db->driverName));

	    $this->createTable(($this->modelProductAvailabilityName)::tableName(), $this->columnsProductAvailability(), AdminHelper::migrateTableOptions($this->db->driverName));

	    $this->addForeignKey('product_availability_warehouse_fk', ($this->modelProductAvailabilityName)::tableName(), 'warehouse_id', ($this->modelWarehouseName)::tableName(), 'id', 'CASCADE', 'CASCADE');
	    $this->addForeignKey('product_availability_product_item_fk', ($this->modelProductAvailabilityName)::tableName(), 'product_item_id', ($this->modelProductItemName)::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
	    $this->dropTable(($this->modelProductAvailabilityName)::tableName());
	    $this->dropTable(($this->modelWarehouseName)::tableName());
    }
}
