<?php

use common\helpers\AdminHelper;
use yii\db\Migration;

/**
 * Class m191218_155321_video_filed_of_category
 */
class m191218_155321_video_filed_of_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach (array_reverse(AdminHelper::migrateTableLangs('video', $this->string(150))) as $_field => $_type) {
            $this->addColumn('category', $_field, $_type ." AFTER image");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach (AdminHelper::migrateTableLangs('video', $this->string(150)) as $_field => $_type) {
            $this->dropColumn('category', $_field, $_type);
        }

        return true;
    }
}
