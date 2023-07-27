<?php

namespace common\models;

use noIT\cache\EntityTrait;
use Yii;
use common\helpers\AdminHelper;
use yii\base\DynamicModel;

/**
 * Class ProductItemEntity
 * @package common\models
 *
 * @property string $priceFormated
 * @property ProductPrice[] $prices
 */

class ProductItemEntity extends BaseContentEntity {
	use EntityTrait;
	public static $activerecordCass = 'common\models\ProductItem';

	public $id;
	public $product_id;
	public $slug;
	public $status;
	public $sku;
	public $ean;
	public $ucgfea;
	public $native_name;
	public $image;
	public $imageUrl;
	public $prices = [];
	public $availabilities = [];
	public $features = [];
	public $featureGroupedValues = [];
	public $featureGroupedValueIds = [];
	public $featureValueIds = [];
	public $packages;

	protected $_name;

	protected $_translatedAttributes = [
		'name',
	];

	public function fields() {
		return array_merge(
			parent::fields(),
			[
				'price' => function() {
					return $this->price;
				},
				'commonPrice' => function() {
					return $this->commonPrice;
				},
				'priceFormated' => function() {
					return $this->priceFormated;
				},
				'commonPriceFormated' => function() {
					return $this->commonPriceFormated;
				},
			]
		);
	}

	public function getName() {
		if ( null === $this->_name ) {
			if ( ! ($this->_name = \Yii::$app->languages->current->getEntityField($this, 'name')) ) {
				$this->_name = \Yii::$app->languages->current->getEntityField($this->product, 'name');
			}
		}
		return $this->_name;
	}

	public function getProduct() {
		return ProductEntity::findOne($this->product_id);
	}

	public function getPrice($type_id = null) {
        $type_id = $this->getTypeId($type_id);

		return isset($this->prices[$type_id]) ? $this->prices[$type_id]->price : null;
	}

	public function getCommonPrice($type_id = null) {
        $type_id = $this->getTypeId($type_id);

		return isset($this->prices[$type_id]) ? $this->prices[$type_id]->common_price : null;
	}

	public function getPriceFormated($type_id = null) {
        $type_id = $this->getTypeId($type_id);

		return Yii::$app->formatter->asDecimal($this->getPrice($type_id));
	}

	public function getCommonPriceFormated($type_id = null) {
        $type_id = $this->getTypeId($type_id);

		return Yii::$app->formatter->asDecimal($this->getCommonPrice($type_id));
	}

	protected function getTypeId($type_id = null) {
        if (   null === $type_id ) {
            $type_id = PriceType::getDefault(false);
        }

        return $type_id;
    }
}
