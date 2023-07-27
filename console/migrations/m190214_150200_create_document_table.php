<?php

use yii\db\Migration;

/**
 * Handles the creation of table `document`.
 */
class m190214_150200_create_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('document', [
            'id' => $this->primaryKey(),
	        'type' => $this->string(255)->notNull(),
	        'name_ru_ru' => $this->string(255)->notNull(),
	        'name_uk_ua' => $this->string(255)->notNull(),
	        'caption_ru_ru' => $this->string(255)->null(),
	        'caption_uk_ua' => $this->string(255)->null(),
	        'cover_image' => $this->string(255)->null(),
	        'file' => $this->string(255)->null(),
            'status' => $this->tinyInteger(2)->unsigned()->notNull()->defaultValue(0),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('document');
    }
}
