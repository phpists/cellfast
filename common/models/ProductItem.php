<?php
namespace common\models;

use common\helpers\AdminHelper;
use common\behaviors\SlugBehavior;
use common\components\projects\Project;
use common\helpers\SiteHelper;
use common\models\soap\E1cGood;
use noIT\cache\EntityModelTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Imagine\Image\ManipulatorInterface;
use mohorev\file\UploadImageBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "product_item".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $product_id
 * @property integer $feature_id
 * @property string $sku
 * @property string $ean
 * @property string $ucgfea
 * @property string $native_name
 * @property integer $sort_order
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ProductType $type
 * @property Project $project
 * @property string $project_id
 * @property Product $product
 * @property float $price
 * @property float $common_price
 * @property integer $availibality
 *
 * @property ProductFeatureValue[] $featureValues
 * @property integer[] $featureGroupedValueIds
 * @property array $packageQuantities
 * @property array $prices
 * @property array $availabilities
 * @property array $packages
 */

class ProductItem extends ActiveRecord
{
	use EntityModelTrait;

	/**
	 * Entity-model class
	 */
	public $entityModel = 'common\models\ProductItemEntity';

	/**
	 * Related models
	 */
	public $productModelClass = 'common\models\Product';
	public $priceModelClass = 'common\models\ProductPrice';
	public $availibalityModelClass = 'common\models\ProductAvailibality';
	protected $_typeModelClass;

	/**
     * @var array
     * Требуется для сохрание ключевых характеристик
     */
    protected $_featureGroupedValueIds;
	protected $_prices;
	protected $_availabilities;
	protected $_packages;

    public function getTypeModelClass() {
	    if (null === $this->_typeModelClass) {
	    	$model = new $this->productModelClass();
		    $this->_typeModelClass = $model->typeModelClass;
	    }
	    return $this->_typeModelClass;
    }

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_item}}';
    }

    public function getImageUrl() {
        return $this->getUploadUrl('image');
    }

	/**
	 * @inheritdoc
	 */
	public static function featureValuesTableName()
	{
		return '{{%product_item_has_pr_feature_value}}';
	}

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
            'image' => [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'image',
                'scenarios' => ['default'],
                'path' => '@cdn/content',
                'url' => '@cdnUrl/content',
                'thumbs' => [
                    'thumb' => ['width' => 200, 'height' => 200,],
                    'thumb_list' => ['width' => 80, 'height' => 50, 'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND],
                    'list' => ['width' => 350, 'height' => 301, 'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND],
                    'cover' => ['width' => 550, 'height' => 450, 'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND],
                ],
            ],
        ];

        $behaviors['sku'] = [
            'class' => SlugBehavior::className(),
            'in_attribute' => 'autosku',
            'out_attribute' => 'sku',
        ];

        foreach (Yii::$app->languages->languages as $language) {
            $behaviors[AdminHelper::getLangField('name', $language)] = [
                'class' => SlugBehavior::className(),
                'in_attribute' => 'native_name',
                'out_attribute' => AdminHelper::getLangField('name', $language),
                'translit' => false,
                'replaced' => false,
                'unique' => false,
            ];
        }

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['project_id'], 'string', 'max' => 20],
            [['type_id', 'product_id'], 'integer'],
            [['sku'], 'string', 'max' => 50],
            [['ucgfea'], 'string', 'max' => 13],
            [['native_name'], 'string', 'max' => 150],
            [AdminHelper::LangsField(['name']), 'string', 'max' => 150],
            [['sort_order', 'status', 'created_at', 'updated_at'], 'integer'],
	        [['sku'], 'unique', 'targetAttribute' => ['sku', 'project_id']],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['default']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->productModelClass, 'targetAttribute' => ['product_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->getTypeModelClass(), 'targetAttribute' => ['type_id' => 'id']],
            [['featureGroupedValueIds', 'prices', 'availabilities', 'packages'], 'safe'],
        ];
    }

    /**
     * Временный геттер для будущего атрибута EAN
     */
    public function getEan()
    {
        return null;
    }

    public function fields() {
	    $result = parent::fields();
	    $result['price'] = function () {
	    	return $this->price;
	    };
	    return $result;
    }

	/**
	 * @return ActiveQuery
	 */
	public function getProduct() {
		return $this->hasOne($this->productModelClass, ['id' => 'product_id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getE1cGood() {
		return $this->hasOne(E1cGood::className(), ['id' => 'e1c_id']);
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

	public function getPrice($type_id = null) {
		if ( null === $type_id ) {
			$type_id = PriceType::getDefault(false);
		}
		return isset($this->prices[$type_id]) ? $this->prices[$type_id]->price : null;
	}

	public function getCommonPrice($type_id = null) {
		if ( null === $type_id ) {
			$type_id = PriceType::getDefault(false);
		}
		return isset($this->prices[$type_id]) ? $this->prices[$type_id]->common_price : null;
	}

	public function getAvailibality($warehouse_id = null) {
		if ( null === $warehouse_id ) {
			$warehouse_id = Warehouse::getDefault(false);
		}
		return isset($this->availabilities[$warehouse_id]) ? $this->availabilities[$warehouse_id]->price : null;
	}

	public function getName() {
		if (!$this->product) {
			return null;
		}
		$name = \Yii::$app->languages->current->getEntityField($this, 'name');
		return $name ? : $this->product->name;
	}

	public function getStatusLabel($warehouse_id = null) {
		$labels = ProductAvailability::statusLabels();
		return ( null !== $this->getAvailibality($warehouse_id) ) && isset($labels[$this->getAvailibality($warehouse_id)]) ? $labels[$this->getAvailibality($warehouse_id)] : null;
	}

	// TODO ! Need refactor
	public function getFeatureValues()
	{
		return $this->hasMany(ProductFeatureValue::className(), ['id' => 'product_feature_value_id'])
		            ->viaTable('{{%product_item_has_pr_feature_value}}', ['product_item_id' => 'id']);
	}

    public function getFeatureGroupedValues()
    {
        $values = [];
        foreach ($this->featureValues as $value) {
            $values[$value->feature_id] = $value;
        }
        return $values;
    }

    public function getFeatureValueIds()
    {
        $values = [];
        foreach ($this->featureValues as $value) {
            $values[] = $value->id;
        }
        return $values;
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProductPrices()
	{
		return $this->hasMany($this->priceModelClass, ['product_item_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProductAvailabilities()
	{
		return $this->hasMany(ProductAvailability::className(), ['product_item_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProductPackages()
	{
		return $this->hasMany(ProductPackage::className(), ['product_item_id' => 'id']);
	}

	public function getPrices() {
		$result = [];
		foreach ($this->productPrices as $price) {
			$result[$price->price_type_id] = $price;
		}
		return $result;
	}

	public function getAvailabilities() {
		$result = [];
		foreach ($this->productAvailabilities as $availability) {
			$result[$availability->warehouse_id] = $availability;
		}
		return $result;
	}

	public function getPackages() {
		$result = [];
		foreach ($this->productPackages as $product_package) {
			$result[$product_package->package_id] = $product_package;
		}
		return $result;
	}

	/** Return auto SKU */
	protected function getAutosku() {
		return uniqid($this->project_id);
	}

	public function getFeatureGroupedValueIds() {
		$values = [];
		foreach ($this->featureValues as $feature_value) {
			$values[$feature_value->feature_id][] = $feature_value->id;
		}
		return $values;
	}

	public function setFeatureGroupedValueIds($values) {
		$this->_featureGroupedValueIds = $values;
	}

	public function setPrices($values) {
		$this->_prices = $values;
	}

	public function setAvailabilities($values) {
		$this->_availabilities = $values;
	}

	public function setPackages($values) {
		$this->_packages = $values;
	}

	public function beforeSave($insert)
	{
		if (null === $this->project_id) {
			$this->project_id = $this->product->project_id;
		}

		if (null === $this->type_id) {
			$this->type_id = $this->product->type_id;
		}

		return parent::beforeSave($insert);
	}

	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		if ( null !== $this->_featureGroupedValueIds ) {
			$this->saveFeatureValues($this->_featureGroupedValueIds);
		}

		if ( null !== $this->_prices ) {
			$this->savePrices($this->_prices);
		}

		if ( null !== $this->_availabilities) {
			$this->saveAvailabilities($this->_availabilities);
		}

		if ( null !== $this->_packages) {
			$this->savePackages($this->_packages);
		}
	}

	protected function saveFeatureValues(&$groupedValues) {
		if (!$groupedValues) {
			// Delete all if empty value
			$groupedValues = [];
		}
		foreach ($groupedValues as $feature_id => $_values) {
			if (!$_values) {
				// Delete all if empty value for each group
				$groupedValues[$feature_id] = [];
			}
		}

		$todel = $toadd = [];
		foreach ($this->featureValues as $_value) {
			$todel[$_value->id] = $_value->id;
		}

		foreach ($groupedValues as $feature_id => $_values) {
			if (!is_array($_values)) {
				$_values = [$_values];
			}
			foreach ($_values as $_value) {
				if (is_object($_value)) {
					$_value = $_value->id;
				}
				if (isset($todel[$_value])) {
					unset($todel[$_value]);
				} else {
					$toadd[] = $_value;
				}
			}
		}

		if (count($todel) > 0) {
			Yii::$app->db->createCommand()->delete("{{%product_item_has_pr_feature_value}}", [
				'product_item_id' => $this->id,
				'product_feature_value_id' => $todel
			])->execute();
		}

		foreach ($toadd as $feature_id) {
			Yii::$app->db->createCommand()->insert("{{%product_item_has_pr_feature_value}}", [
				'product_item_id' => $this->id,
				'product_feature_value_id' => $feature_id,
				'updated_at' => new Expression('NOW()'),
			])->execute();
		}
		$groupedValues = null;
	}

	public function saveDefaultPrice($price) {
	    $type_id = PriceType::getDefault(false);

	    $this->savePrices([$type_id => $price]);
    }

	protected function savePrices($prices) {
		foreach ($prices as $type_id => $price) {
            $data['price_type_id'] = $type_id;
            $data['product_item_id'] = $this->id;
            $data['price'] = $price;

			// Clear prices
//            ProductPrice::deleteAll(['product_item_id' => $price['product_item_id']]);

            // Load or create model
			if ( null === ($model = ProductPrice::findOne(['product_item_id' => $this->id, 'price_type_id' => $type_id]) ) ) {
				$model = new ProductPrice();
			}

			// Save model
			if ( !$model->load($data, '') || !$model->save() ) {
			    $this->addError('prices', SiteHelper::getErrorMessages($model->errors, false));
			}
		}
	}

	protected function saveAvailabilities($availabilities) {
		foreach ($availabilities as $warehouse_id => $availability) {
			$availability['warehouse_id'] = $warehouse_id;
			$availability['product_item_id'] = $this->id;
			if ( null === ($model = ProductAvailability::findOne(['product_item_id' => $this->id, 'warehouse_id' => $warehouse_id]) ) ) {
				$model = new ProductAvailability();
			}
			if ( !$model->load($availability, '') || !$model->save() ) {
                $this->addError('availabilities', SiteHelper::getErrorMessages($model->errors, false));
			}
		}
	}

	protected function savePackages($packages) {
		foreach ($packages as $package_id => $quantity) {
			$quantity['package_id'] = $package_id;
			$quantity['product_item_id'] = $this->id;
			if ( null === ($model = ProductPackage::findOne(['product_item_id' => $this->id, 'package_id' => $package_id]) ) ) {
				$model = new ProductPackage();
			}
			if ( !$model->load($quantity, '') || !$model->save() ) {
                $this->addError('packages', SiteHelper::getErrorMessages($model->errors, false));
			}
		}
	}

	public function prepareEntity() {
		$itemData = $this->toArray(array_merge(
			[
				'id',
				'product_id',
				'slug',
				'status',
				'sku',
				'ucgfea',
				'native_name',
				'image',
			],
			AdminHelper::LangsField([
				'name',
			])
		));

		$itemData['imageUrl'] = $this->getImageUrl();
		$itemData['prices'] = $this->prices;
		$itemData['availabilities'] = $this->availabilities;
		$itemData['featureGroupedValues'] = $this->featureGroupedValues;
		$itemData['featureGroupedValueIds'] = $this->featureGroupedValueIds;
		$itemData['featureValueIds'] = $this->featureValueIds;
		$itemData['packages'] = [
            'packet' => $this->e1cGood ? intval($this->e1cGood->packing_ratio) : null,
            'pallet' => $this->e1cGood ? intval($this->e1cGood->pallet_ratio) : null,
        ];

		return $itemData;
	}

    public function getPriceFormated($type_id = null) {
        $type_id = $this->getTypeId($type_id);

        return Yii::$app->formatter->asDecimal($this->getPrice($type_id));
    }

    protected function getTypeId($type_id = null) {
        if (   null === $type_id ) {
            $type_id = PriceType::getDefault(false);
        }

        return $type_id;
    }
}
