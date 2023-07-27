<?php

namespace common\models;

use common\components\projects\Project;

/**
 * Class Article
 * @package common\models
 * @property Project $project
 * @property Tag[] $tags
 * @property Category[] $categories
 */
class Article extends BaseContent
{
	const PAGE_SIZE = 3;

	public $imagesUploadEntity = 'article';

    public static function tableName()
    {
        return '{{%article}}';
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('{{%article_has_tag}}', ['article_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
            ->viaTable('{{%article_has_category}}', ['article_id' => 'id']);
    }
}