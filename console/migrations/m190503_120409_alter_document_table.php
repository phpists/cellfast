<?php

use yii\db\Migration;

/**
 * Class m190503_120409_alter_document_table
 */
class m190503_120409_alter_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('document', 'project_id', 'VARCHAR(20) NOT NULL');

		$this->createIndex(
			'idx-project_id',
			'document',
			'project_id'
		);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190503_120409_alter_document_table cannot be reverted.\n";

        $this->dropColumn('document', 'project_id');

        return true;
    }
}
