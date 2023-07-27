<?php

namespace common\models;

use common\components\projects\Project;
use Yii;
use yii\base\Exception;

/**
 * Class Content
 * @package common\models
 * @property integer $project_id
 * @property Project $project
 * @property Tag[] $tags
 * @property Category[] $categories
 */

class BaseContent extends \noIT\content\models\Content
{
	public $imagesUploadClass = 'common\models\ContentImage';

	/**
	 * Entity-model class
	 */
	protected $_entityModel;

	public static function tableName() {
		throw new Exception("Can not use class BaseContent as entity");
	}

	public function behaviors()
	{
		$behaviors = parent::behaviors();

		if (isset($behaviors['image'])) {
			$behaviors['image']['path'] = '@cdn/content/{project.alias}';
			$behaviors['image']['url'] = '@cdnUrl/content/{project.alias}';

			$behaviors['image_list']['path'] = '@cdn/content/{project.alias}';
			$behaviors['image_list']['url'] = '@cdnUrl/content/{project.alias}';
		}

		return $behaviors;
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
}