<?php

namespace noIT\location\migrations;

use noIT\core\helpers\AdminHelper;
use yii\db\Migration;


/**
 * Class migrate_location
 */
class migrate_location extends Migration
{
	protected $modelCountryName = 'noIT\location\models\LocationCountry';
	protected $modelRegionName = 'noIT\location\models\LocationRegion';
	protected $modelPlaceName = 'noIT\location\models\LocationPlace';

	protected function columnsCountry() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'native_name' => $this->string(150)->notNull(),
				'image' => $this->string(250),
				'flag' => $this->string(250),
			],
			AdminHelper::migrateTableLangs('name', $this->string(150)),
			AdminHelper::migrateTableLangs('body', $this->string(150)),
			AdminHelper::migrateTableSort($this),
			AdminHelper::migrateTableStatus($this, ($this->modelCountryName)::STATUS_ACTIVE),
			AdminHelper::migrateTableTS($this)
		);
	}

	protected function columnsRegion() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'country_id' => $this->integer()->notNull(),
				'native_name' => $this->string(150)->notNull(),
				'image' => $this->string(250),
			],
			AdminHelper::migrateTableLangs('name', $this->string(150)),
			AdminHelper::migrateTableLangs('body', $this->string(150)),
			AdminHelper::migrateTableSort($this),
			AdminHelper::migrateTableStatus($this, ($this->modelRegionName)::STATUS_ACTIVE),
			AdminHelper::migrateTableTS($this)
		);
	}

	protected function columnsPlace() {
		return array_merge(
			AdminHelper::migrateTablePK($this),
			[
				'region_id' => $this->integer()->notNull(),
				'country_id' => $this->integer()->notNull(),
				'native_name' => $this->string(150)->notNull(),
				'slug' => $this->string(255)->notNull(),
				'image' => $this->string(250),
				'flag' => $this->string(250),
				'is_default' => $this->boolean()->defaultValue(false),
				'lat' => $this->float(7),
				'lng' => $this->float(7),
			],
			AdminHelper::migrateTableLangs('name', $this->string(150)),
			AdminHelper::migrateTableLangs('body', $this->string(150)),
			AdminHelper::migrateTableSort($this),
			AdminHelper::migrateTableStatus($this, ($this->modelPlaceName)::STATUS_ACTIVE),
			AdminHelper::migrateTableTS($this)
		);
	}

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
	    $this->createTable(($this->modelCountryName)::tableName(), $this->columnsCountry(), AdminHelper::migrateTableOptions($this->db->driverName));
	    $this->createTable(($this->modelRegionName)::tableName(), $this->columnsRegion(), AdminHelper::migrateTableOptions($this->db->driverName));
	    $this->createTable(($this->modelPlaceName)::tableName(), $this->columnsPlace(), AdminHelper::migrateTableOptions($this->db->driverName));

	    $this->addForeignKey('location_region_country_fk', ($this->modelRegionName)::tableName(), 'country_id', ($this->modelCountryName)::tableName(), 'id', 'CASCADE', 'CASCADE');
	    $this->addForeignKey('location_place_region_fk', ($this->modelPlaceName)::tableName(), 'region_id', ($this->modelRegionName)::tableName(), 'id', 'CASCADE', 'CASCADE');
	    $this->addForeignKey('location_place_country_fk', ($this->modelPlaceName)::tableName(), 'country_id', ($this->modelCountryName)::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
	    $this->dropTable(($this->modelPlaceName)::tableName());
	    $this->dropTable(($this->modelRegionName)::tableName());
	    $this->dropTable(($this->modelCountryName)::tableName());
    }
}
