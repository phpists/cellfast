<?php

use yii\db\Migration;

/**
 * Handles the creation of table `partner`.
 */
class m190208_145722_create_partner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('partner', [
            'id' => $this->primaryKey(),
	        'type' => $this->string(255)->notNull(),
	        'location_region_id' => $this->integer(11)->null(),

	        'name_ru_ru' => $this->string(255)->null(),
	        'name_uk_ua' => $this->string(255)->null(),

	        'caption_ru_ru' => $this->string(255)->null(),
	        'caption_uk_ua' => $this->string(255)->null(),

	        'address_ru_ru' => $this->string(255)->null(),
	        'address_uk_ua' => $this->string(255)->null(),

	        'coordinate' => $this->string(255)->null(),

	        'phones' => $this->text()->null(),

	        'website' => $this->string(255)->null(),

	        'logotype' => $this->string(255)->null(),

	        'status' => $this->tinyInteger(2)->unsigned()->notNull()->defaultValue(0),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

	    $this->addForeignKey(
		    'fk-partner-location_region_id',
		    'partner',
		    'location_region_id',
		    'location_region',
		    'id',
		    'CASCADE',
		    'CASCADE'
	    );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk-partner-location_region_id', 'partner');

        $this->dropTable('partner');

        return true;
    }
}
