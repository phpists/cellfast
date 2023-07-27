<?php
namespace common\models;

use noIT\cache\EntityTrait;

class ProductFeatureEntity extends BaseContentEntity {
	use EntityTrait;

	public static $activerecordCass = 'common\models\ProductFeature';

	public $id;
	public $project_id;
	public $native_name;
	public $slug;
	public $multiple;
	public $overall;
	public $value_type;
	public $admin_widget;
	public $filter_widget;
	public $image;
	public $status;
    public $values;
    public $created_at;
    public $updated_at;

	protected $_translatedAttributes = [
		'name',
		'caption',
	];

	public function getName() {
		return \Yii::$app->languages->current->getEntityField($this, 'name');
	}

	public function getCaption() {
		return \Yii::$app->languages->current->getEntityField($this, 'caption');
	}

//	public function getValue($id) {
//		return isset($this->values[$id]) ? $this->values[$id] : null;
//	}
}