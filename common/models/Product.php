<?php

namespace common\models;

use common\helpers\AdminHelper;
use common\behaviors\SlugBehavior;
use common\helpers\SiteHelper;
use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * @property ProductItem[] $items
 */

class Product extends BaseContent
{
	/**
	 * Entity-model class
	 */
	public $entityModel = 'common\models\ProductEntity';

	/**
	 * Related models
	 */
	public $typeModelClass = 'common\models\ProductType';
	public $itemModelClass = 'common\models\ProductItem';
	public $featureGroupModelClass = 'common\models\ProductFeature';
	public $featureValueModelClass = 'common\models\ProductFeatureValue';
	public $propertyModelClass = 'common\models\ProductProperty';

	/**
	 * Names of tables
	 */
	protected $_featureValuesTableName = '{{%product_has_product_feature_value}}';
	protected $_featureValuesItemTableName = '{{%product_item_has_pr_feature_value}}';

	/**
	 * Product images
	 */
	public $imagesUploadClass = 'common\models\ProductImage';
	public $imagesUploadEntity = 'product';

	/**
	 * For saved
	 */
	protected $_items;
	protected $_category;
	protected $_featureGroupedValueIds;
	protected $_propeties;
	protected $_actualDefineFeatures;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%product}}';
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		unset($behaviors['image_list']);

		$behaviors['image']['path'] = '@cdn/content';
		$behaviors['image']['url'] = '@cdnUrl/content';

		$behaviors['slug'] = [
			'class' => SlugBehavior::className(),
			'in_attribute' => 'native_name',
			'out_attribute' => 'slug',
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

			$behaviors[AdminHelper::getLangField('seo_h1', $language)] = [
				'class' => SlugBehavior::className(),
				'in_attribute' => 'native_name',
				'out_attribute' => AdminHelper::getLangField('seo_h1', $language),
				'translit' => false,
				'replaced' => false,
				'unique' => false,
			];
		}

		unset($behaviors['relations']);

		return $behaviors;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['native_name', 'type_id'], 'required'],
			[['project_id'], 'string', 'max' => 20],
			[['status', 'created_at', 'updated_at'], 'integer'],
			[['native_name', 'slug'], 'string', 'max' => 100],
			[['slug'], 'unique', 'targetAttribute' => ['slug', 'project_id']],
			[AdminHelper::LangsField(['name', 'seo_h1', 'seo_title']), 'string', 'max' => 150],
			[AdminHelper::LangsField(['teaser', 'body', 'seo_description', 'seo_keywords']), 'string'],
			['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['default']],
			[['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->typeModelClass, 'targetAttribute' => ['type_id' => 'id']],
			[['items', 'featureGroupedValueIds', 'properties'], 'safe'],
		];
	}

	public function getProject()
	{
		return Yii::$app->projects->getProject($this->project_id);
	}

	public function getType()
	{
		return $this->hasOne($this->typeModelClass, ['id' => 'type_id']);
	}

	/**
	 * Return default category of the related product-type
	 *
	 * @return bool|Category|mixed
	 */
	public function getCategory()
	{
		// TODO Set exception id type not set
		return $this->type->category;
	}

	/**
     * Список комбинаций товара
	 * @return \yii\db\ActiveQuery
	 */
	public function getItems()
	{
		return $this->hasMany($this->itemModelClass, ['product_id' => 'id'])->orderBy([AdminHelper::FIELDNAME_SORT => SORT_ASC])->where(['>', 'product_item.status', 0]);
	}

	public function getFeatureValues()
	{
		return $this->hasMany($this->featureValueModelClass, ['id' => 'product_feature_value_id'])
		            ->viaTable('{{%product_has_product_feature_value}}', ['product_id' => 'id']);
	}

	public function setActualDefineFeatures($value)
	{
		$this->_actualDefineFeatures = $value;
	}

	public function getActualDefineFeatures($product_items_ids = [])
	{
		if (null === $this->_actualDefineFeatures) {
			$itemTableName = call_user_func([$this->itemModelClass, 'tableName']);
			$featureValuesTableName = call_user_func([$this->itemModelClass, 'featureValuesTableName']);

			if (empty($product_items_ids)) {

				$query = (new Query())
					->select(['product_feature_value_id'])
					->from($featureValuesTableName)
					->innerJoin("{$itemTableName} pi", "pi.id = {$featureValuesTableName}.product_item_id")
					->where(["pi.product_id" => $this->id]);
//			->andWhere(['!=', 'pi.status', ProductItem::STATUS_UNAVAILABLE]);
			} else {
				$query = (new Query())
					->select(['value_id' => 'p.product_feature_value_id'])
					->from(['p' => 'product_item_has_pr_feature_value'])
					->where(['p.product_item_id' => $product_items_ids]);
			}

			$featureValues = ProductFeatureValue::find()->where(['id' => $query->column()])->all();

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
					$sort_array[strval($value->{$value_attribute})] = $id;
					ksort($sort_array);
				}

				$new_array = [];
				foreach ($sort_array as $id) {
					$new_array[$id] = $result[$feature_id]['values'][$id];
				}

				$result[$feature_id]['values'] = $new_array;
			}

			$this->_actualDefineFeatures = $result;
		}
		return $this->_actualDefineFeatures;
	}

	public function getProperties()
	{
		return $this->hasMany($this->propertyModelClass, ['product_id' => 'id'])->orderBy([AdminHelper::FIELDNAME_SORT => SORT_ASC]);
	}

	public function getImageUrl()
	{
		return $this->image ? $this->getUploadUrl('image') : null;
	}

	public function getFeatureGroupedValues()
	{
		$values = [];
		foreach ($this->featureValues as $value) {
			$values[$value->feature_id][$value->id] = $value;
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

	public function fields()
	{
		return array_merge(
			[
				'id',
				'type_id',
				'slug',
				'native_name',
				'image' => 'imageUrl',
				'images' => 'images',
				'status',
			],
			AdminHelper::LangsField([
				'name',
				'teaser',
				'body',
			])
		);
	}

	public function setItems($items)
	{
		$this->_items = $items;
	}

	public function getFeatureGroupedValueIds()
	{
		$values = [];
		foreach ($this->featureValues as $featureValue) {
			$values[$featureValue->feature_id][] = $featureValue->id;
		}
		return $values;
	}

	public function setFeatureGroupedValueIds($values)
	{
		$this->_featureGroupedValueIds = $values;
	}

	public function setProperties($values)
	{
		$this->_propeties = $values;
	}

	/**
	 * Формирование данных для загрузки в modelEntity
	 */
	protected function prepareEntity()
	{
		$data = $this->toArray();

		$data['items'] = $data['features'] = [];
		foreach ($this->getItems()->select('id')->all() as $item) {
			$itemData = ProductItemEntity::findOne($item);

			$_featureValueIds = $item->featureValueIds;
			sort($_featureValueIds);
			$data['items_features_map'][implode(':', $_featureValueIds)] = $item->id;

			$data['items'][ $item->id ] = $itemData;
		}

		$data['definedFeatures'] = $this->getActualDefineFeatures();
		$data['featureGroupedValues'] = $this->featureGroupedValues;
		$data['featureGroupedValueIds'] = $this->featureGroupedValueIds;
		$data['featureValueIds'] = $this->featureValueIds;

		$data['properties'] = $this->properties;

		return $data;
	}

	public function beforeSave($insert)
	{
		if (is_null($this->status) || $this->status == '') {
			$this->status = self::STATUS_DRAFT;
		}

		if (null === $this->project_id) {
			$this->project_id = $this->type->project_id;
		}

		return parent::beforeSave($insert);
	}

	public function afterSave($insert, $changedAttributes)
	{
		if (null !== $this->_items) {
			$this->saveItems($this->_items);
		}

		if (null !== $this->_featureGroupedValueIds) {
			$this->saveFeatureValues($this->_featureGroupedValueIds);
		}

		if (null !== $this->_propeties) {
			$this->saveProperties($this->_propeties);
		}

		parent::afterSave($insert, $changedAttributes);
	}

	protected function saveItems(&$items)
	{
		if (!$items) {
			// Delete all if empty value
			$items = [];
		}

		$todel = $toadd = $toupdate = [];
		foreach ($this->items as $item) {
			$todel[$item->id] = $item->id;
		}

		foreach (array_values($items) as $i => $item) {
			if (is_object($item)) {
				$item = $item->toArray();
			}

			$item[AdminHelper::FIELDNAME_SORT] = $i;
			$item['product_id'] = $this->id;

			if (!empty($item['id'])) {
				if (isset($todel[$item['id']])) {
					unset($todel[$item['id']]);
				}
				$toupdate[$item['id']] = $item;
			} elseif (empty($item['id'])) {
				$toadd[] = $item;
			}
		}

		if (count($todel) > 0) {
			call_user_func([$this->itemModelClass, 'deleteAll'], ['id' => $todel]);
		}

		foreach ($toadd as $item) {
			$_value = Yii::createObject($this->itemModelClass);
			$_value->load($item, '');

			if (!$_value->save()) {
				Yii::$app->session->setFlash('error', SiteHelper::getErrorMessages($_value->errors));
			}
		}
		foreach ($toupdate as $item) {
			/** @var ProductItem $_value */
			if ( !($_value = call_user_func([$this->itemModelClass, 'find'])->where(['id' => $item['id']])->one()) ) {
				continue;
			}
			$_value->load($item, '');
			if (!$_value->save()) {
				Yii::$app->session->setFlash('error', SiteHelper::getErrorMessages($_value->errors));
			}
		}
		$items[$item['id']] = null;
	}

	protected function saveFeatureValues(&$grouped_values)
	{
		if (!$grouped_values) {
			// Delete all if empty value
			$grouped_values = [];
		}
		foreach ($grouped_values as $feature_id => $_values) {
			if (!$_values) {
				// Delete all if empty value for each group
				$grouped_values[$feature_id] = [];
			}
		}

		$todel = $toadd = [];
		foreach ($this->featureValues as $_value) {
			$todel[$_value->id] = $_value->id;
		}

		foreach ($grouped_values as $feature_id => $_values) {
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
			Yii::$app->db->createCommand()->delete("{{%product_has_product_feature_value}}", [
				'product_id' => $this->id,
				'product_feature_value_id' => $todel
			])->execute();
		}
		$i = 0;

		foreach ($toadd as $feature_id) {
			Yii::$app->db->createCommand()->insert("{{%product_has_product_feature_value}}", [
				'product_id' => $this->id,
				'product_feature_value_id' => $feature_id,
				'updated_at' => new Expression('NOW()'),
			])->execute();
		}
		$grouped_values = null;
	}

	protected function saveProperties(&$properties)
	{
		if (null === $properties) {
			return;
		}
		if (!$properties) {
			// Delete all if empty value
			$properties = [];
		}

		call_user_func([$this->propertyModelClass, 'deleteAll'], ['product_id' => $this->id]);

		$i = 0;
		foreach ($properties as $property) {
			$property['product_id'] = $this->id;
			$property['sort_order'] = $i;
			$model = new $this->propertyModelClass($property);
			if (!$model->save()) {}
			$i++;
		}

		$properties = null;
	}

	public function findBySku($sku)
	{
		return ProductItem::find()->where(['product_id' => $this->id, 'sku' => trim($sku)])->one();
	}
}
