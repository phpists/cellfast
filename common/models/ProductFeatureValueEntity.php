<?php
namespace common\models;

use noIT\cache\EntityTrait;

class ProductFeatureValueEntity extends BaseContentEntity {
	use EntityTrait;

	public static $activerecordCass = 'common\models\ProductFeatureValue';

	public $id;
	public $project_id;
	public $feature_id;
	public $value;
	public $value_str;
	public $value_flt;
	public $value_txt;
	public $value_int;
	public $value_obj;
	public $slug;
	public $sort_order;
	public $passive;
	public $created_at;
	public $updated_at;

	protected $_translatedAttributes = [
		'value_label'
	];

	public function getValue_label() {
		return \Yii::$app->languages->current->getEntityField($this, 'value_label');
	}
}