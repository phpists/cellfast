<?php

use common\helpers\AdminHelper;
use yii\db\Migration;

/**
 * Class m171207_102805_product_item_package
 */
class m171207_102805_product_item_package extends Migration
{
	protected $productItemModelName = 'common\models\ProductItem';
	protected $packageModelName = 'common\models\Package';
	protected $productPackageModelName = 'common\models\ProductPackage';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    $this->createTable(
		    ($this->productPackageModelName)::tableName(),
		    array_merge(
			    [
				    'product_item_id' => $this->integer()->notNull(),
				    'package_id' => $this->integer()->notNull(),
				    'quantity' => $this->float(3),
			    ],
			    AdminHelper::migrateTableTS($this)
		    ),
		    AdminHelper::migrateTableOptions($this->db->driverName)
	    );

	    $this->addPrimaryKey('price_type_pk', ($this->productPackageModelName)::tableName(), ['product_item_id', 'package_id']);

	    $this->addForeignKey('product_pachage_product_item_fk', ($this->productPackageModelName)::tableName(), 'product_item_id', ($this->productItemModelName)::tableName(), 'id', 'CASCADE', 'CASCADE');
	    $this->addForeignKey('product_pachage_package_fk', ($this->productPackageModelName)::tableName(), 'package_id', ($this->packageModelName)::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable(($this->productPackageModelName)::tableName());
    }
}
