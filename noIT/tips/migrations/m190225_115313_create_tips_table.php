<?php
namespace noIT\tips\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `tips`.
 */
class m190225_115313_create_tips_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tips', [
            'id' => $this->primaryKey(),
	        'model' => $this->string(255)->notNull(),
	        'attribute' => $this->string(255)->notNull(),
	        'body' => $this->text()->null(),
        ]);

        $this->createIndex(
        	'u-idx-tips_model_and_attribute',
	        'tips',
	        ['model', 'attribute'],
	        true
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tips');

        return true;
    }
}
