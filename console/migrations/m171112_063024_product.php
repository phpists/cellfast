<?php

use yii\db\Migration;
use common\helpers\AdminHelper;
use common\models\Category;

class m171112_063024_product extends Migration
{
    protected $typeModelName = 'common\models\ProductType';
    protected $productModelName = 'common\models\Product';
	protected $itemModelName = 'common\models\ProductItem';
	protected $categoryModelName = 'common\models\Category';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            ($this->typeModelName)::tableName(),
            array_merge(
                AdminHelper::migrateTablePK($this),
                [
                    'native_name' => $this->string(150)->notNull(),
                    'project_id' => $this->string(20)->notNull(),
                ],
                AdminHelper::migrateTableLangs('name', $this->string(150)),
                AdminHelper::migrateTableStatus($this, ($this->typeModelName)::STATUS_ACTIVE),
                AdminHelper::migrateTableTS($this)
            ),
            AdminHelper::migrateTableOptions($this->db->driverName)
        );

        $this->createIndex('product_type_project_id_index', ($this->typeModelName)::tableName(), 'project_id');

        // Add relation with category
	    $this->addColumn(
		    ($this->categoryModelName)::tableName(),
		    'product_type_id',
		    $this->integer()->notNull() ." AFTER `parent_id`"
	    );

	    $this->addForeignKey(
		    'category_product_type_id_fk',
		    ($this->categoryModelName)::tableName(),
		    'product_type_id',
		    ($this->typeModelName)::tableName(),
		    'id',
		    'RESTRICT',
		    'RESTRICT'
	    );

        $this->createTable(
            ($this->productModelName)::tableName(),
            array_merge(
                AdminHelper::migrateTablePK($this),
                [
                    'project_id' => $this->string(20)->notNull(),
                    'type_id' => $this->integer()->notNull(),
                    'native_name' => $this->string(150)->notNull(),
                    'slug' => $this->string(255)->notNull(),
                    'image' => $this->string(255),
                ],
                AdminHelper::migrateTableLangs('name', $this->string(150)->notNull()),
                AdminHelper::migrateTableLangs('seo_h1', $this->string(150)),
                AdminHelper::migrateTableLangs('seo_title', $this->string(150)),
                AdminHelper::migrateTableLangs('teaser', $this->text()),
                AdminHelper::migrateTableLangs('body', $this->text()),
                AdminHelper::migrateTableLangs('seo_description', $this->text()),
                AdminHelper::migrateTableLangs('seo_keywords', $this->text()),
                AdminHelper::migrateTableStatus($this, ($this->productModelName)::STATUS_ACTIVE),
                AdminHelper::migrateTableTS($this),
	            AdminHelper::migrateTablePublished($this)
            ),
            AdminHelper::migrateTableOptions($this->db->driverName)
        );

        $this->addForeignKey('product_type_id_fk', ($this->productModelName)::tableName(), 'type_id', ($this->typeModelName)::tableName(), 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('product_project_id_index', ($this->productModelName)::tableName(), 'project_id');


        $this->createTable(
            ($this->itemModelName)::tableName(),
            array_merge(
                AdminHelper::migrateTablePK($this),
                [
                    'product_id' => $this->integer()->notNull(),
                    'project_id' => $this->string(20)->notNull(),
                    'type_id' => $this->integer()->notNull(),
                    'native_name' => $this->string(150),
                    'sku' => $this->string(50)->notNull(),
                ],
                AdminHelper::migrateTableLangs('name', $this->string(150)),
                [
                    'image' => $this->string(250),
                    AdminHelper::FIELDNAME_SORT => $this->integer()->defaultValue(0),
                ],
                AdminHelper::migrateTableTS($this)
            ),
            AdminHelper::migrateTableOptions($this->db->driverName)
        );

	    $this->createIndex('product_item_project_id_index', ($this->itemModelName)::tableName(), 'project_id');

	    $this->addForeignKey('product_item_product_id_fk', ($this->itemModelName)::tableName(), 'product_id', ($this->productModelName)::tableName(), 'id', 'CASCADE', 'CASCADE');
	    $this->addForeignKey('product_item_type_id_fk', ($this->itemModelName)::tableName(), 'type_id', ($this->typeModelName)::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable(($this->itemModelName)::tableName());
        $this->dropTable(($this->productModelName)::tableName());
        $this->dropTable(($this->typeModelName)::tableName());
    }
}
