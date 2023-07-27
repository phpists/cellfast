<?php

use yii\db\Migration;

/**
 * Class m190620_083752_create_about_main_page_table
 */
class m190620_083752_create_about_main_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->createTable('{{%about_main_page}}', [
		    'id' => $this->primaryKey(),
		    'project_id' => $this->string(20),
		    'name_ru_ru' => $this->string(255)->null(),
		    'name_uk_ua' => $this->string(255)->null(),
		    'cover' => $this->string(255)->null(),
		    'body_ru_ru' => $this->text()->null(),
		    'body_uk_ua' => $this->text()->null(),

		    'info_image_1' => $this->string(255)->null(),
		    'info_image_2' => $this->string(255)->null(),

		    'info_teaser_1_ru_ru' => $this->text()->null(),
		    'info_teaser_1_uk_ua' => $this->text()->null(),

		    'info_teaser_2_ru_ru' => $this->text()->null(),
		    'info_teaser_2_uk_ua' => $this->text()->null(),

		    'created_at' => $this->integer()->notNull(),
		    'updated_at' => $this->integer()->notNull(),
	    ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->dropTable('{{%about_main_page}}');

	    return true;
    }
}
