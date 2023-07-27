<?php

use yii\db\Migration;

/**
 * Class m180102_180102_soap_log
 */
class m180102_180102_soap_log extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('e1c_log',
            [   'id' => $this->bigPrimaryKey(),
                'level' => $this->integer(),
                'category' => $this->string(),
                'log_time' => $this->double(),
                'prefix' => $this->text(),
                'message' => $this->text(),
            ]
        );
        $this->createIndex('log_time_ind', 'e1c_log', 'log_time');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('e1c_log');
    }
}