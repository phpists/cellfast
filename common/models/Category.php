<?php
namespace common\models;

use baibaratsky\yii\behaviors\model\SerializedAttributes;
use common\components\projects\Project;
use common\helpers\AdminHelper;
use Yii;
use common\behaviors\SlugBehavior;
use yii\behaviors\TimestampBehavior;
use mohorev\file\UploadImageBehavior;
use common\components\tree\TreeBehavior;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $project_id
 * @property integer $product_type_id
 * @property integer $parent_id
 * @property string $path
 * @property integer $depth
 * @property string $native_name
 * @property string $slug
 * @property string $slug_outer
 * @property string $image
 * @property string $seo_h1
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $name
 * @property string $description
 * @property string $caption
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $video
 * @property string $manuals
 *
 * @property Project $project
 * @property Product[] $products
 * @property ProductType $product_type
 */

class Category extends \noIT\category\models\Category
{
	const PAGE_SIZE = 12;

    public $_features;

    protected $_sub_ids;

    public static $cache;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    public function behaviors()
    {
        $behaviors = [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
            'slug' => [
                'class' => SlugBehavior::className(),
                'in_attribute' => 'native_name',
                'out_attribute' => 'slug',
            ],
            'image' => [
                'class' => UploadImageBehavior::className(),
                'attribute' => 'image',
                'scenarios' => ['default'],
                'path' => '@cdn/category/{project.alias}',
                'url' => '@cdnUrl/category/{project.alias}',
                'thumbs' => [
                    'thumb' => ['width' => 200, 'height' => 200,],
                    'thumb_list' => ['height' => 50],
                ],
            ],
            'tree' => [
                'class' => TreeBehavior::className(),
                'keyNameGroup' => null,
                'keyNamePath' => 'path',
            ],
        ];

        $_serializedAttributes = [];

        foreach (Yii::$app->languages->languages as $language) {
            $_serializedAttributes[] = AdminHelper::getLangField('manuals', $language);
        }

        $behaviors['serializedAttributes'] = [
            'class' => SerializedAttributes::className(),
            'attributes' => $_serializedAttributes,
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

        foreach (Yii::$app->languages->languages as $language) {
            $behaviors[AdminHelper::getLangField('seo_title', $language)] = [
                'class' => SlugBehavior::className(),
                'in_attribute' => AdminHelper::getLangField('name', $language),
                'out_attribute' => AdminHelper::getLangField('seo_title', $language),
                'translit' => false,
                'replaced' => false,
                'unique' => false,
            ];
            $behaviors[AdminHelper::getLangField('seo_h1', $language)] = [
                'class' => SlugBehavior::className(),
                'in_attribute' => AdminHelper::getLangField('name', $language),
                'out_attribute' => AdminHelper::getLangField('seo_h1', $language),
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
            [['native_name', 'project_id'], 'required'],
            [['project_id'], 'string', 'max' => 20],
            [['parent_id', 'product_type_id', 'depth', 'status', 'created_at', 'updated_at'], 'integer'],
            [['path', 'slug_outer'], 'string', 'max' => 255],
            [['native_name', 'slug'], 'string', 'max' => 100],
            [AdminHelper::LangsField(['name', 'seo_h1', 'seo_title']), 'string', 'max' => 150],
            [AdminHelper::LangsField(['description', 'seo_description', 'seo_keywords']), 'string'],
            [AdminHelper::LangsField(['video']), 'string', 'max' => 150],
            [AdminHelper::LangsField(['manuals']), 'safe'],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['default']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge([
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent'),
            'product_type_id' => Yii::t('app', 'Product type'),
            'product_type.native_name' => Yii::t('app', 'Product type'),
            'project_id' => Yii::t('app', 'Project'),
            'path' => Yii::t('app', 'Path'),
            'depth' => Yii::t('app', 'Depth'),
            'native_name' => Yii::t('app', 'Native Name'),
            'slug' => Yii::t('app', 'Slug'),
            'slug_outer' => Yii::t('app', 'Slug Outer'),
            'image' => Yii::t('app', 'Image'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ],
            AdminHelper::LangsFieldLabels('name', 'Name'),
            AdminHelper::LangsFieldLabels('seo_title', 'Seo Title'),
            AdminHelper::LangsFieldLabels('seo_h1', 'Seo H1'),
            AdminHelper::LangsFieldLabels('description', 'Description'),
            AdminHelper::LangsFieldLabels('seo_description', 'Seo Description'),
            AdminHelper::LangsFieldLabels('seo_keywords', 'Seo Keywords'),
            AdminHelper::LangsFieldLabels('video', 'Video'),
            AdminHelper::LangsFieldLabels('manuals', 'Manuals')
        );
    }

    public function getProject() {
        return Yii::$app->projects->getProject($this->project_id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeatures()
    {
        return $this->hasMany(
        	ProductFeature::className(),
            ['id' => 'product_feature_id'])
        ->viaTable(
	        ProductTypeHasProductFeature::tableName(),
            ['product_type_id' => 'product_type_id']
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeaturesForFilter()
    {
        return ProductFeature::find()
            ->innerJoin(ProductTypeHasProductFeature::tableName(), ProductTypeHasProductFeature::tableName() .'.product_feature_id = '. ProductFeature::tableName() .'.id')
            ->where([
                ProductTypeHasProductFeature::tableName() .'.product_type_id' => $this->product_type_id,
                'in_filter' => 1,
            ])
            ->orderBy([ProductTypeHasProductFeature::tableName() .'.sort_order' => SORT_ASC]);
    }

    /**
     * Вовзращает массив группированных значений свойств товаров
     * @return \yii\db\ActiveQuery
     */
    public function getFeaturesValue($params)
    {

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct_type()
    {
        return $this->hasOne(ProductType::className(), ['id' => 'product_type_id']);
    }

    public function setFeatures($value) {
        $this->_features = empty($value) ? [] : $value;
    }

    public function getName() {
        return \Yii::$app->languages->current->getEntityField($this, 'name');
    }

    public function getCaption($length = 10) {
        return StringHelper::truncateWords($this->description, $length);
    }

    public function getDescription() {
        return \Yii::$app->languages->current->getEntityField($this, 'description');
    }

    public function getSeo_title() {
        return \Yii::$app->languages->current->getEntityField($this, 'seo_title');
    }

    public function getVideo() {
        return \Yii::$app->languages->current->getEntityField($this, 'video');
    }

    public function getManuals() {
        return \Yii::$app->languages->current->getEntityField($this, 'manuals');
    }

    public function getSeo_h1() {
        return \Yii::$app->languages->current->getEntityField($this, 'seo_h1');
    }

    public function getSeo_description() {
        return \Yii::$app->languages->current->getEntityField($this, 'seo_description');
    }

    public function getSeo_keywords() {
        return \Yii::$app->languages->current->getEntityField($this, 'seo_keywords');
    }

	/**
	 *
	 * @return ArrayDataProvider
	 */
	public function getProducts($filter = []) {
		$searchModel = new ProductItemSearch();

		$params = ['ProductItemSearch' => [
			'project_id' => \Yii::$app->id,
			'type_id' => $this->product_type_id ? : ($this->getSubTypeIds()),
			'status' => Product::STATUS_ACTIVE
		]];

		foreach ($filter as $key => $values) {
			$params['ProductItemSearch'][$key] = $values;
		}

		return $searchModel->search($params);
	}

	/**
	 *
	 * @return ActiveQuery
	 */
	public function getProductsQuery($filter = []) {
		$searchModel = new ProductItemSearch();

		$params = ['ProductItemSearch' => [
			'project_id' => \Yii::$app->id,
			'type_id' => $this->product_type_id ? : ($this->getSubTypeIds()),
			'status' => Product::STATUS_ACTIVE
		]];

		foreach ($filter as $key => $values) {
			$params['ProductItemSearch'][$key] = $values;
		}

		return $searchModel->buildQuery($params);
	}

    /** @return ActiveQuery */
    /*public function getProducts($withSubCategories = true) {
        if ($withSubCategories) {
            $ids = $this->subIds;
        } else {
            $ids = [$this->id];
        }

        $query = Product::find()->innerJoin('product_group', 'product_group.id = product.group_id')->where(['product_group.category_id' => $ids]);

        return $query;
    }*/

    public function getSubIds() {
        if (is_null($this->_sub_ids)) {
            $this->_sub_ids = [];
            foreach ($this->getAllChildren()->select(['category.id'])->column() as $id) {
                $this->_sub_ids[] = $id;
            }
        }
        return $this->_sub_ids;
    }

    public function getSubTypeIds() {
        if (is_null($this->_sub_ids)) {
            $this->_sub_ids = [];
            foreach ($this->getAllChildren()->select(['category.product_type_id'])->column() as $id) {
                $this->_sub_ids[] = $id;
            }
        }
        return $this->_sub_ids;
    }

    public static function findOne( $condition ) {
    	if ( !is_array($condition) ) {
    		$condition = ['id' => $condition];
	    }
    	$cacheKey = [];
    	foreach ($condition as $key => $value) {
    		$cacheKey[] = "{$key}[{$value}]";
	    }

	    return Yii::$app->cache->getOrSet(implode("", $cacheKey), function() use ($condition) {return parent::findOne( $condition );});
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        return parent::save($runValidation, $attributeNames);
    }
}
