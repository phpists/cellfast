<?php

use yii\db\Migration;

/**
 * Class m190619_102233_alter_category_table
 */
class m190619_102233_alter_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('category', 'slug_outer', 'VARCHAR(255) NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190619_102233_alter_category_table cannot be reverted.\n";

	    $this->dropColumn('category', 'slug_outer');

	    return true;
    }

}
