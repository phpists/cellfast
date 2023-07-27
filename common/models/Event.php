<?php
namespace common\models;

/**
 * Class Event
 * @package common\models
 * @property Project $project
 * @property Tag[] $tags
 * @property Category[] $categories
 */

use Yii;
use common\components\projects\Project;

class Event extends \noIT\content\models\Content
{
	const PAGE_SIZE = 6;

	public $imagesUploadClass = 'common\models\ContentImage';
	public $imagesUploadEntity = 'event';

	public static function tableName()
	{
		return '{{%event}}';
	}

	public function behaviors()
	{
		return parent::behaviors();
	}

	public function rules()
	{
		return array_merge(parent::rules(),
			[
				[['project_id'], 'string', 'max' => 20],
				[['image', 'image_list'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['default']],
				[['image'], 'image', 'minWidth' => 350, 'minHeight' => 290],
				[['image_list'], 'image', 'minWidth' => 350, 'minHeight' => 215]
			]
		);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return array_merge(
			['project_id' => Yii::t('app', 'Project')],
			parent::attributeLabels()
		);
	}

	public function getProject() {
		return Yii::$app->projects->getProject($this->project_id);
	}

	public function getTags()
	{
		return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
		            ->viaTable('{{%event_has_tag}}', ['event_id' => 'id']);
	}

	public function getCategories()
	{
		return $this->hasMany(Category::className(), ['id' => 'category_id'])
		            ->viaTable('{{%event_has_category}}', ['event_id' => 'id']);
	}
}
