<?php
namespace noIT\gallery\migrations;

use yii\db\Migration;
use common\helpers\AdminHelper;

class migrate_gallery_image extends Migration
{
    protected $modelName = 'noIT\gallery\models\GalleryImage';

    protected function columnsImageItem() {
        return array_merge(
            AdminHelper::migrateTablePK($this),
            [
            	/** TODO - добавить группировку по галереям */
//            	'gallery_id' => $this->integer(),
                'entity' => $this->string(50),
                'entity_id' => $this->integer(),
                'slug' => $this->string(255),
                'src' => $this->string(255),
            ],
	        AdminHelper::migrateTableLangs('title', $this->string(250)),
	        AdminHelper::migrateTableLangs('caption', $this->text()),
	        AdminHelper::migrateTableLangs('alt', $this->string(250)),
	        AdminHelper::migrateTableStatus($this, ($this->modelName)::STATUS_ACTIVE),
            AdminHelper::migrateTableSort($this),
            AdminHelper::migrateTableTS($this)
        );
    }

    public function safeUp()
    {
        $this->createTable(($this->modelName)::tableName(), $this->columnsImageItem(), AdminHelper::migrateTableOptions($this->db->driverName));

        $this->createIndex(($this->modelName)::tableName() ."_entity_id_index", ($this->modelName)::tableName(), ['entity', 'entity_id']);
    }

    public function safeDown()
    {
        $this->dropTable(($this->modelName)::tableName());
    }
}
