<?php

use yii\db\Migration;

/**
 * Class m190506_091542_alter_partner_table
 */
class m190506_091542_alter_partner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('partner', 'projects', 'VARCHAR(255) NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190506_091542_alter_partner_table cannot be reverted.\n";

        $this->dropColumn('partner', 'projects');

        return true;
    }
}
