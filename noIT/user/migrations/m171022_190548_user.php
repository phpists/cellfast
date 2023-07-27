<?php

use yii\db\Migration;
use noIT\user\models\User;
use noIT\core\helpers\AdminHelper;

class migration_user extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'status' => $this->smallInteger(2)->notNull()->defaultValue(User::STATUS_ACTIVE),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], AdminHelper::migrateTableOptions($this->db->driverName));
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
