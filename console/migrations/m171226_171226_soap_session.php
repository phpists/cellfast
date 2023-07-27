<?php

use yii\db\Migration;

/**
 * Class m171226_171226_soap_session
 */
class m171226_171226_soap_session extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('e1c_session',
            [   'id' => $this->primaryKey(10),
                'guid' => "BINARY(16) NOT NULL",
                'entity' => $this->string(25)->notNull(),
                'size' => $this->integer(10)->unsigned()->notNull(),
                'status' => "ENUM('W', 'E', 'F') NOT NULL COMMENT 'W-wait, E-end, F-fail'",
                'duration' => "MEDIUMINT UNSIGNED",
                'timestamp' => $this->timestamp()
                    ->defaultValue('0000-00-00 00:00:00'),
                'created_at' => $this->timestamp()
                    ->defaultExpression('CURRENT_TIMESTAMP()'),
                'updated_at' => $this->timestamp()
                    ->defaultExpression('CURRENT_TIMESTAMP()')
                    ->append('ON UPDATE CURRENT_TIMESTAMP()'),
            ]
        );
        $this->createIndex('status_ind', 'e1c_session', 'status');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('e1c_session');
    }
}