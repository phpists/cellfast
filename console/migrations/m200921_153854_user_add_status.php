<?php

use yii\db\Migration;

/**
 * Class m200921_153854_user_add_status
 */
class m200921_153854_user_add_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'status', $this->tinyInteger(2)->defaultValue(\noIT\user\models\User::STATUS_ACTIVE));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'status');

        return true;
    }
}
