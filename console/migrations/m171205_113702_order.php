<?php

use common\models\User;
use common\helpers\AdminHelper;
use yii\db\Migration;

// TODO - перенести в отдельный модуль

/**
 * Class m171205_113702_order
 */
class m171205_113702_order extends Migration
{
	protected $orderModelName = 'common\models\Order';
	protected $orderStatusModelName = 'common\models\OrderStatus';
	protected $orderProductModelName = 'common\models\OrderProduct';
	protected $productItemModelName = 'common\models\ProductItem';
	protected $warehouseModelName = 'common\models\Warehouse';

	protected function columnsOrderStatus() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'native_name' => $this->string(150),
				'cancel' => $this->boolean()->defaultValue(false),
				'accept' => $this->boolean()->defaultValue(false),
				'success' => $this->boolean()->defaultValue(false),
				'e1c_slug' => $this->string(20),
			],
			AdminHelper::migrateTableSort($this),
			AdminHelper::migrateTableTS($this)
		);
	}

	protected function columnsOrder() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'status_id' => $this->integer()->defaultValue(null)->comment('If null - in cart'),
				'user_id' => $this->integer()->notNull(),
				'warehouse_id' => $this->integer()->defaultValue(null),
				'delivery_id' => $this->integer()->defaultValue(null),
				'payment_id' => $this->integer()->defaultValue(null),
				'number' => $this->string(50),
				'token' => $this->string(64)->unique(),
				'is_quick' => $this->boolean()->defaultValue(false),
				'order_comment' => $this->text(),
				'delivery_comment' => $this->text(),
				'delivery_data' => $this->text()->comment('serialized data'),
				'delivery_cost' => $this->float()->defaultValue(0),
				'payment_comment' => $this->text(),
				'payment_data' => $this->text()->comment('serialized data'),
				'payment_cost' => $this->float()->defaultValue(0),
				'discount_abs' => $this->float()->defaultValue(0),
				'discount_percent' => $this->float()->defaultValue(0),
			],
			AdminHelper::migrateTableTS($this)
		);
	}

	protected function columnsOrderProduct() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'order_id' => $this->integer()->notNull(),
				'product_item_id' => $this->integer()->notNull(),
				'params' => $this->text(),
				'quantity' => $this->float(3),
				'price' => $this->float(3),
				'summ' => $this->float(3),
			],
			AdminHelper::migrateTableTS($this)
		);
	}

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
    	$this->createTable(($this->orderStatusModelName)::tableName(), $this->columnsOrderStatus(), AdminHelper::migrateTableOptions($this->db->driverName));
    	$this->createTable(($this->orderModelName)::tableName(), $this->columnsOrder(), AdminHelper::migrateTableOptions($this->db->driverName));
    	$this->createTable(($this->orderProductModelName)::tableName(), $this->columnsOrderProduct(), AdminHelper::migrateTableOptions($this->db->driverName));

    	$this->addForeignKey('order_user_fk', ($this->orderModelName)::tableName(), 'user_id', User::tableName(), 'id', 'CASCADE', 'RESTRICT');
    	$this->addForeignKey('order_status_fk', ($this->orderModelName)::tableName(), 'status_id', ($this->orderStatusModelName)::tableName(), 'id', 'CASCADE', 'RESTRICT');
    	$this->addForeignKey('order_warehouse_fk', ($this->orderModelName)::tableName(), 'warehouse_id', ($this->warehouseModelName)::tableName(), 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable(($this->orderProductModelName)::tableName());
        $this->dropTable(($this->orderModelName)::tableName());
        $this->dropTable(($this->orderStatusModelName)::tableName());
    }
}
