<?php

class m171118_152803_event extends \noIT\content\migrations\migrate_content {
	protected $modelName = 'common\models\Event';

	protected function columnsContent()
	{
		return array_merge(
			[
				'project_id' => $this->string(20)->notNull(),
			],
			parent::columnsContent()
		);
	}

	public function safeUp()
	{
		parent::safeUp();

		$this->createIndex('event_project', ($this->modelName)::tableName(), 'project_id');
	}

	public function safeDown()
	{
		$this->dropIndex('event_project', ($this->modelName)::tableName());

		parent::safeDown();
	}
}
