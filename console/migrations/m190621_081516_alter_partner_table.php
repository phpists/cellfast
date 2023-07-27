<?php

use yii\db\Migration;

/**
 * Class m190621_081516_alter_partner_table
 */
class m190621_081516_alter_partner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->addColumn('partner', 'email', 'VARCHAR(255) NULL');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190621_081516_alter_partner_table cannot be reverted.\n";

	    $this->dropColumn('partner', 'email');

	    return true;
    }
}
