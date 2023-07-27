<?php

use yii\db\Migration;

/**
 * Class m200506_163536_alter_about_us_table
 */
class m200506_163536_alter_about_us_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('about_us', 'cover_2', 'VARCHAR(255) NULL');

        $this->addColumn('about_us', 'teaser_2_ru_ru', 'TEXT NULL');
        $this->addColumn('about_us', 'teaser_2_uk_ua', 'TEXT NULL');

        $this->addColumn('about_us', 'teaser_3_ru_ru', 'TEXT NULL');
        $this->addColumn('about_us', 'teaser_3_uk_ua', 'TEXT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('about_us', 'cover_2');

        $this->dropColumn('about_us', 'teaser_2_ru_ru');
        $this->dropColumn('about_us', 'teaser_2_uk_ua');

        $this->dropColumn('about_us', 'teaser_3_ru_ru');
        $this->dropColumn('about_us', 'teaser_3_uk_ua');

        return true;
    }
}
