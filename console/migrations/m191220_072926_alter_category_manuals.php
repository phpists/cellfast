<?php

use common\helpers\AdminHelper;
use yii\db\Migration;

/**
 * Class m191220_072926_alter_category_manuals
 */
class m191220_072926_alter_category_manuals extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach (array_reverse(AdminHelper::migrateTableLangs('manuals', $this->text())) as $_field => $_type) {
            $this->addColumn('category', $_field, $_type ." AFTER image");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach (AdminHelper::migrateTableLangs('manuals', $this->text()) as $_field => $_type) {
            $this->dropColumn('category', $_field, $_type);
        }

        return true;
    }
}
