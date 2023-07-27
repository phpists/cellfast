<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%about_us}}`.
 */
class m190619_132720_create_about_us_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%about_us}}', [
            'id' => $this->primaryKey(),
	        'project_id' => $this->string(20),
	        'name_ru_ru' => $this->string(255)->null(),
	        'name_uk_ua' => $this->string(255)->null(),
	        'slug' => $this->string(255)->null(),
	        'cover' => $this->string(255)->null(),
	        'teaser_ru_ru' => $this->text()->null(),
	        'teaser_uk_ua' => $this->text()->null(),
	        'body_ru_ru' => $this->text()->null(),
	        'body_uk_ua' => $this->text()->null(),
	        'video' => $this->string(255)->null(),
            'body_2_ru_ru' => $this->text()->null(),
            'body_2_uk_ua' => $this->text()->null(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%about_us}}');
    }
}
