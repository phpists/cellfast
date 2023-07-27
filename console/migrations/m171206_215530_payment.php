<?php

use common\helpers\AdminHelper;
use yii\db\Migration;

/**
 * Class m171206_215530_payment
 */
class m171206_215530_payment extends Migration
{
	protected $paymentModelName = 'common\models\Payment';
	protected $deliveryModelName = 'common\models\Delivery';
	protected $orderModelName = 'common\models\Order';
	protected $paymentDeliveryTableName = 'payment_has_delivery';

    /**
     * @inheritdoc
     */
	protected function columnsPayment() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'native_name' => $this->string(150),
				'VAT' => $this->float(3)->defaultValue(0)->comment("Fractional"),
			],
			AdminHelper::migrateTableLangs('name', $this->string(150)),
			AdminHelper::migrateTableLangs('description', $this->text()),
			AdminHelper::migrateTableSort($this),
			AdminHelper::migrateTableStatus($this, ($this->paymentModelName)::STATUS_ACTIVE),
			AdminHelper::migrateTableTS($this)
		);
	}

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    $this->createTable(($this->paymentModelName)::tableName(), $this->columnsPayment(), AdminHelper::migrateTableOptions($this->db->driverName));
	    $this->createTable($this->paymentDeliveryTableName, [
		    'payment_id' => $this->integer()->notNull(),
		    'delivery_id' => $this->integer()->notNull()
	    ], AdminHelper::migrateTableOptions($this->db->driverName));

	    $this->addForeignKey("{$this->paymentDeliveryTableName}_payment_fk", $this->paymentDeliveryTableName, 'payment_id', ($this->paymentModelName)::tableName(), 'id', 'CASCADE', 'RESTRICT');
	    $this->addForeignKey("{$this->paymentDeliveryTableName}_delivery_fk", $this->paymentDeliveryTableName, 'delivery_id', ($this->deliveryModelName)::tableName(), 'id', 'CASCADE', 'RESTRICT');

	    $this->addForeignKey('order_payment_fk', ($this->orderModelName)::tableName(), 'payment_id', ($this->paymentModelName)::tableName(), 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown() {
	    $this->dropTable($this->paymentDeliveryTableName);
	    $this->dropForeignKey('order_payment_fk', ($this->orderModelName)::tableName());
	    $this->dropTable(($this->paymentModelName)::tableName());
    }
}
