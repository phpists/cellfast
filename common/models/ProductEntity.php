<?php

namespace common\models;

use noIT\cache\EntityTrait;
use noIT\imagecache\helpers\ImagecacheHelper;
use Yii;
use yii\db\Query;

/**
 * Class ProductEntity
 * @package common\models
 *
 * @property ProductItemEntity $item
 * @property ProductItemEntity[] $items
 * @property array $definedFeaturesMap
 */

class ProductEntity extends BaseContentEntity
{
	use EntityTrait;

	public static $activerecordCass = 'common\models\Product';

	public $type_id;
	public $slug;
	public $native_name;
	public $status;
	public $image;
	public $imageUrl;
	public $images = [];
	public $items = [];
	public $items_features_map = [];
	public $definedFeatures = [];
	public $features = [];
	public $featureValueIds = [];
	public $featureGroupedValues = [];
	public $featureGroupedValueIds = [];
	public $properties = [];

	protected $_item;

	protected $_featuresTable;

	protected $_translatedAttributes = [
		'name',
		'teaser',
		'body',
	];

	public function getName() {
		return \Yii::$app->languages->current->getEntityField($this, 'name');
	}

	public function getTeaser() {
		return \Yii::$app->languages->current->getEntityField($this, 'teaser');
	}

	public function getBody() {
		return \Yii::$app->languages->current->getEntityField($this, 'body');
	}

	/**
	 * @return ProductType
	 */
	public function getType()
	{
		return ProductType::findOne($this->type_id);
	}

	public function getCategory()
	{
		return $this->type->category;
	}

    public function setItem($value) {
	    $this->_item = $value;
    }

    public function getItem($feature_values = null)
	{
		if ( $feature_values ) {
			$this->_item = $this->getItemByFeatures($feature_values);
		}

		if (!$this->_item) {
            $this->_item = $this->getDefaultItem();
        }

        return $this->_item;
	}

	public function getDefaultItem()
	{
		if ( null === $this->_item ) {
			if ( ! $this->items ) {
				return new ProductItemDummy( [ 'product' => $this ] );
			}
			$this->_item = $this->items[ array_keys( $this->items )[0] ];
		}
		return $this->_item;
	}

	public function getItemByFeatures($feature_values)
	{
		sort( $feature_values );
		$feature_values = implode( ':', $feature_values );

		if ( isset( $this->items_features_map[ $feature_values ] ) && isset( $this->items[ $this->items_features_map[ $feature_values ] ] ) ) {
			return $this->items[ $this->items_features_map[ $feature_values ] ];
		} else {
			return null;
		}
	}

	public function getItemById($item_id)
	{
		return $item_id && isset($this->items[$item_id]) ? $this->items[$item_id] : $this->getDefaultItem();
	}

	/**
	 * @return array
	 */
	public function getFeaturesTable()
	{
		if ( null === $this->_featuresTable ) {
			$this->_featuresTable = [];
			foreach ($this->featureGroupedValues as $group_id => $values) {
				$group = Yii::$app->productFeature->getGroup($group_id);
				$row = [
					'name' => $group->name,
					'value' => [],
					'value_label' => [],
				];
				foreach ($values as $value) {
					$row['value'][] = $value->value;
					$row['value_label'][] = $value->value_label;
				}
				if ($row['value']) {
					$this->_featuresTable[] = $row;
				}
			}
		}
		return $this->_featuresTable;
	}

	public function getPropertiesTable()
	{
		$result = [];
		foreach ($this->properties as $property) {
			$result[] = [
				'name' => $property->name,
				'value' => [$property->value],
				'value_label' => [$property->value],
			];
		}
		return $result;
	}

	public function getDefinedFeaturesMap()
	{
		$map = [];

		foreach ($this->items as $item) {
			$map[implode(':', $item->featureValueIds)] = $item->toArray([
				'id',
				'price',
				'commonPrice',
				'imageUrl',
				'sku',
                'packages'
//				'ucgfea',
			]);
		}

		return $map;
	}

	public static function findOneFromArray($array)
	{
	    /** @var ProductEntity $product */
	    $product = self::findOne($array['id']);

	    $featuresQuery = (new Query())
            ->select(['value_id' => 'p.product_feature_value_id'])
            ->from(['p' => 'product_item_has_pr_feature_value'])
            ->where(['p.product_item_id' => $array['items']]);

        $featureValues = ProductFeatureValue::find()->where(['id' => $featuresQuery->column()])->all();

        $result = [];
        foreach ($featureValues as $value) {
            if (!isset($result[$value->feature_id])) {
                $result[$value->feature_id] = [
                    'entity' => ProductFeatureEntity::findOne($value->feature_id),
                    'values' => [],
                ];
            }
            $result[$value->feature_id]['values'][$value->id] = $value;
        }

        foreach ($result as $feature_id => $item) {
            $value_type = $item['entity']->value_type;

            $sort_array = [];

            foreach ($item['values'] as $id => $value) {
                $value_attribute = "value_{$value_type}";
                $sort_array[$value->{$value_attribute}] = $id;
                ksort($sort_array);
            }

            $new_array = [];
            foreach ($sort_array as $id) {
                $new_array[$id] = $result[$feature_id]['values'][$id];
            }

            $result[$feature_id]['values'] = $new_array;
        }

        $product->definedFeatures = $result;

        return $product;
    }
}
