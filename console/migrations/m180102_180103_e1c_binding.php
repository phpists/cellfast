<?php

use yii\db\Migration;

/**
 * Class m171213_054048_e1c_binding
 */
class m180102_180103_e1c_binding extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->addColumn(\common\models\ProductType::tableName(), 'e1c_id', $this->integer()->unsigned());
		$this->addColumn(\common\models\PriceType::tableName(), 'e1c_id', $this->integer()->unsigned());
		$this->addColumn(\common\models\ProductItem::tableName(), 'e1c_id', $this->integer()->unsigned());
		$this->addColumn(\common\models\Warehouse::tableName(), 'e1c_id', $this->integer()->unsigned());
		$this->addColumn(\common\models\Order::tableName(), 'e1c_id', $this->integer()->unsigned());
		$this->addColumn(\common\models\User::tableName(), 'e1c_id', $this->integer()->unsigned());

		$this->addForeignKey('e1c_group_of_good_fk', \common\models\ProductType::tableName(), 'e1c_id', \common\models\soap\E1cGroupOfGood::tableName(), 'id');
		$this->addForeignKey('e1c_type_of_price_fk', \common\models\PriceType::tableName(), 'e1c_id', \common\models\soap\E1cTypeOfPrice::tableName(), 'id');
		$this->addForeignKey('e1c_good_fk', \common\models\ProductItem::tableName(), 'e1c_id', \common\models\soap\E1cGood::tableName(), 'id');
		$this->addForeignKey('e1c_warehouse_fk', \common\models\Warehouse::tableName(), 'e1c_id', \common\models\soap\E1cWarehouse::tableName(), 'id');
		$this->addForeignKey('e1c_head_of_order_fk', \common\models\Order::tableName(), 'e1c_id', \common\models\soap\E1cHeadOfOrder::tableName(), 'id');
		$this->addForeignKey('e1c_client_fk', \common\models\User::tableName(), 'e1c_id', \common\models\soap\E1cClient::tableName(), 'id');

		$this->addColumn(\common\models\soap\E1cGroupOfGood::tableName(), 'exclude_binding', $this->boolean()->defaultValue(false));
		$this->addColumn(\common\models\soap\E1cTypeOfPrice::tableName(), 'exclude_binding', $this->boolean()->defaultValue(false));
		$this->addColumn(\common\models\soap\E1cGood::tableName(), 'exclude_binding', $this->boolean()->defaultValue(false));
		$this->addColumn(\common\models\soap\E1cWarehouse::tableName(), 'exclude_binding', $this->boolean()->defaultValue(false));
		$this->addColumn(\common\models\soap\E1cClient::tableName(), 'exclude_binding', $this->boolean()->defaultValue(false));
		$this->addColumn(\common\models\soap\E1cHeadOfOrder::tableName(), 'exclude_binding', $this->boolean()->defaultValue(false));
	}

	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropColumn(\common\models\ProductType::tableName(), 'e1c_id');
		$this->dropColumn(\common\models\PriceType::tableName(), 'e1c_id');
		$this->dropColumn(\common\models\ProductItem::tableName(), 'e1c_id');
		$this->dropColumn(\common\models\Warehouse::tableName(), 'e1c_id');
		$this->dropColumn(\common\models\Order::tableName(), 'e1c_id');
		$this->dropColumn(\common\models\User::tableName(), 'e1c_id');

		$this->dropColumn(\common\models\soap\E1cGroupOfGood::tableName(), 'exclude_binding');
		$this->dropColumn(\common\models\soap\E1cTypeOfPrice::tableName(), 'exclude_binding');
		$this->dropColumn(\common\models\soap\E1cGood::tableName(), 'exclude_binding');
		$this->dropColumn(\common\models\soap\E1cWarehouse::tableName(), 'exclude_binding');
		$this->dropColumn(\common\models\soap\E1cClient::tableName(), 'exclude_binding');
		$this->dropColumn(\common\models\soap\E1cHeadOfOrder::tableName(), 'exclude_binding');
	}
}
