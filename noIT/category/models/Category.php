<?php
namespace noIT\category\models;

use noIT\content\models\Content;
use yii\db\ActiveRecord;

/**
 * Class Category
 * @package noIT\category\models
 */
// TODO - Change extends of Content or with trait
class Category extends ActiveRecord
{
    const STATUS_DRAFT = -1;
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 10;

    public $subcategories = [];

    public function getName() {
        return \Yii::$app->languages->current->getEntityField($this, 'name');
    }

    public function getH1() {
        return \Yii::$app->languages->current->getEntityField($this, 'seo_h1');
    }

    public function getTitle() {
        return \Yii::$app->languages->current->getEntityField($this, 'seo_title');
    }

    public function getDescription() {
        return \Yii::$app->languages->current->getEntityField($this, 'description');
    }

	/**
	 * @param $slug
	 *
	 * @return array|null|Content|Category
	 */
	public static function findBySlug($slug) {
		static $result;
		$slug = strtolower($slug);
		if ( !isset($result[$slug]) ) {
			$result[$slug] = self::find()->where(['slug' => $slug])->one();
		}
		return $result[$slug];
	}
}