<?php

use yii\db\Migration;

/**
 * Handles the creation of table `feedback`.
 */
class m190328_154532_create_feedback_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('feedback', [
	        'id' => $this->primaryKey(),
	        'ip' => $this->string('20'),
	        'name' => $this->string('150'),
	        'email' => $this->string('255'),
	        'phone' => $this->string('255'),
	        'message' => $this->text(),
	        'source' => $this->string('150'),
	        'data' => $this->text(),
	        'model' => $this->string('150'),
	        'status' => $this->smallInteger(2)->notNull()->defaultValue(10),
	        'created_at' => $this->integer()->notNull(),
	        'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('feedback');
    }
}
