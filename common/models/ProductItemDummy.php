<?php

namespace common\models;

use common\components\projects\Project;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * This is the model class of dummy-item of product
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $product_id
 * @property integer $feature_id
 * @property string $sku
 * @property string $ucgfea
 * @property string $native_name
 * @property boolean $status
 * @property integer $sort_order
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ProductType $type
 * @property Project $project
 * @property string $project_id
 * @property Product $product
 * @property null $price
 * @property null $commonPrice
 * @property ProductFeatureValue[] $featureValues
 * @property integer[] $feature_grouped_value_ids
 */

class ProductItemDummy extends Model {
	/**
	 * @var $product Product
	 */
	public $product;
	public $prices = [];
	public $availabilities = [];

	public $itemModelClass = 'common\models\ProductItem';
	public $itemModelObject;
	public $packages;

	public function init() {
		parent::init();
	}

	public function getId() {
		return null;
	}

	/**
	 * @return ProductType
	 */
	public function getType() {
		return $this->product->type;
	}

	public function getProject() {
		return $this->product->project;
	}

	public function getProject_id() {
		return $this->product->project_id;
	}

	public function getPrice() {
		return null;
	}

	public function getPriceFormated() {
		return null;
	}

	public function getCommonPrice() {
		return null;
	}

	public function getCommonPriceFormated() {
		return '';
	}

	public function getStatus() {
		return $this->status;
	}

	public function getNative_name() {
		return null;
	}

	public function getName() {
		return $this->product->name;
	}

	public function getSku() {
		return null;
	}

	public function getUcgfea() {
		return null;
	}

	public function getFeatureValues() {
		return null;
	}

	public function getImage() {
		return null;
	}

	public function getImageUrl() {
		return null;
	}
}
