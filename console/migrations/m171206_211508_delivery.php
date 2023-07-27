<?php

use common\helpers\AdminHelper;
use yii\db\Migration;

// TODO - перенести в отдельный модуль

/**
 * Class m171206_211508_delivery
 */
class m171206_211508_delivery extends Migration
{
	protected $deliveryModelName = 'common\models\Delivery';
	protected $warehouseModelName = 'common\models\Warehouse';
	protected $orderModelName = 'common\models\Order';
	protected $deliveryWarehouseTableName = 'delivery_has_warehouse';

	protected function columnsDelivery() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'native_name' => $this->string(150),
				'is_self' => $this->boolean()->defaultValue(false),
			],
			AdminHelper::migrateTableLangs('name', $this->string(150)),
			AdminHelper::migrateTableLangs('description', $this->text()),
			AdminHelper::migrateTableSort($this),
			AdminHelper::migrateTableStatus($this, ($this->deliveryModelName)::STATUS_ACTIVE),
			AdminHelper::migrateTableTS($this)
		);
	}

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    $this->createTable(($this->deliveryModelName)::tableName(), $this->columnsDelivery(), AdminHelper::migrateTableOptions($this->db->driverName));
	    $this->createTable($this->deliveryWarehouseTableName, [
	    	'delivery_id' => $this->integer()->notNull(),
	    	'warehouse_id' => $this->integer()->notNull()
	    ], AdminHelper::migrateTableOptions($this->db->driverName));

	    $this->addForeignKey("{$this->deliveryWarehouseTableName}_delivery_fk", $this->deliveryWarehouseTableName, 'delivery_id', ($this->deliveryModelName)::tableName(), 'id', 'CASCADE', 'RESTRICT');
	    $this->addForeignKey("{$this->deliveryWarehouseTableName}_warehouse_fk", $this->deliveryWarehouseTableName, 'warehouse_id', ($this->warehouseModelName)::tableName(), 'id', 'CASCADE', 'RESTRICT');

	    $this->addForeignKey('order_delivery_fk', ($this->orderModelName)::tableName(), 'delivery_id', ($this->deliveryModelName)::tableName(), 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
	    $this->dropTable($this->deliveryWarehouseTableName);
	    $this->dropForeignKey('order_delivery_fk', ($this->orderModelName)::tableName());
	    $this->dropTable(($this->deliveryModelName)::tableName());
    }
}
