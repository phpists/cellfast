<?php


use common\helpers\AdminHelper;
use yii\db\Migration;

/**
 * Class m171207_085144_package
 */
class m171207_085144_package extends Migration
{
	protected $packageModelName = 'common\models\Package';
	protected $productTypeModelName = 'common\models\ProductType';
	protected $packageProductTypeName = 'product_type_has_package';

	/**
	 * @inheritdoc
	 */
	protected function columnsPackage() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'native_name' => $this->string(150),
				'e1c_slug' => $this->string(20),
			],
			AdminHelper::migrateTableLangs('name', $this->string(150)),
			AdminHelper::migrateTableLangs('caption', $this->text()),
			AdminHelper::migrateTableSort($this),
			AdminHelper::migrateTableStatus($this, ($this->packageModelName)::STATUS_ACTIVE),
			AdminHelper::migrateTableTS($this)
		);
	}

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    $this->createTable(($this->packageModelName)::tableName(), $this->columnsPackage(), AdminHelper::migrateTableOptions($this->db->driverName));
	    $this->createTable($this->packageProductTypeName, [
		    'package_id' => $this->integer()->notNull(),
		    'product_type_id' => $this->integer()->notNull()
	    ], AdminHelper::migrateTableOptions($this->db->driverName));

	    $this->addForeignKey("{$this->packageProductTypeName}_package_fk", $this->packageProductTypeName, 'package_id', ($this->packageModelName)::tableName(), 'id', 'CASCADE', 'RESTRICT');
	    $this->addForeignKey("{$this->packageProductTypeName}_product_type_fk", $this->packageProductTypeName, 'product_type_id', ($this->productTypeModelName)::tableName(), 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
	    $this->dropTable($this->packageProductTypeName);
	    $this->dropTable(($this->packageModelName)::tableName());
    }
}
