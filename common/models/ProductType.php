<?php
namespace common\models;

use common\behaviors\SlugBehavior;
use common\components\projects\Project;
use common\helpers\AdminHelper;
use common\helpers\SiteHelper;
use common\models\soap\E1cGroupOfGood;
use voskobovich\linker\LinkerBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product_type".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $e1c_id
 *
 * @property Project $project
 * @property Product[] $products
 * @property ProductItem[] $product_items
 * @property ProductTypeHasProductFeature[] $product_features
 * @property ProductTypeHasProductFeature[] $product_features_define
 * @property ProductTypeHasProductFeature[] $product_features_basic
 * @property Category $category
 * @property Category[] $categories
 * @property Package[] $packages
 */

class ProductType extends ActiveRecord
{
    protected $productModelClass = 'common\models\Product';
    protected $categoryModelClass = 'common\models\Category';
    protected $itemModelClass = 'common\models\ProductItem';
    protected $featuresModelClass = 'common\models\ProductFeature';

    protected $_product_features;

    const STATUS_DRAFT = -1;
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_type}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::className(),
        ];

        foreach (AdminHelper::getLanguages() as $language) {
            $behaviors[AdminHelper::getLangField("name", $language)] = [
                'class' => SlugBehavior::className(),
                'in_attribute' => 'native_name',
                'out_attribute' => AdminHelper::getLangField("name", $language),
                'translit' => false,
                'replaced' => false,
                'unique' => false,
            ];
        }

        $behaviors['relations'] = [
		    'class' => LinkerBehavior::className(),
		    'relations' => [
			    'rel_packages' => 'packages',
		    ],
	    ];

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
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['native_name'], 'string', 'max' => 150],
            [AdminHelper::LangsField(['name']), 'string', 'max' => 150],
            [['products', 'product_product_features', 'product_features'], 'safe'],
	        [['rel_packages'], 'each', 'rule' => ['integer']],
	        [['e1c_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge([
                'id' => Yii::t('app', 'ID'),
                'project_id' => Yii::t('app', 'Project'),
                'native_name' => Yii::t('app', 'Native Name'),
                'rel_packages' => Yii::t('app', 'Packages'),
                'status' => Yii::t('app', 'Status'),
                'created_at' => Yii::t('app', 'Created At'),
                'updated_at' => Yii::t('app', 'Updated At'),
            ],
            AdminHelper::LangsFieldLabels('name', 'Name')
        );
    }

    public function getProject() {
        return Yii::$app->projects->getProject($this->project_id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany($this->productModelClass, ['type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct_items()
    {
        return $this->hasMany($this->itemModelClass, ['type_id' => 'id']);
    }

    public function getName() {
        return \Yii::$app->languages->current->getEntityField($this, 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct_features()
    {
        return $this->hasMany(ProductTypeHasProductFeature::className(), ['product_type_id' => 'id'])
            ->orderBy(['sort_order' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct_features_define()
    {
        return $this->hasMany(ProductTypeHasProductFeature::className(), ['product_type_id' => 'id'])->andWhere(['defining' => ProductTypeHasProductFeature::DEFINING_ON]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct_features_basic()
    {
        return $this->hasMany(ProductTypeHasProductFeature::className(), ['product_type_id' => 'id'])->andWhere(['!=', 'defining', ProductTypeHasProductFeature::DEFINING_ON]);
    }

    public function setProduct_features($values)
    {
        $this->_product_features = $values;
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		$categoryClass = $this->getCategoryClass();
		return $this->hasOne($categoryClass::className(), ['product_type_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategories()
	{
		$categoryClass = $this->getCategoryClass();
		return $this->hasMany($categoryClass::className(), ['product_type_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPackages()
	{
		return $this->hasMany(Package::className(), ['id' => 'package_id'])
		            ->viaTable('{{%product_type_has_package}}', ['product_type_id' => 'id']);
	}

	public function getE1cGroup() {
		return $this->hasOne(E1cGroupOfGood::className(), ['id' => 'e1c_id']);
	}

	/**
	 * Формирование данных для загрузки в modelEntity
	 * TODO - Реализовать ProductTypeEntity
	 */
	protected function prepareEntity() {
		$data = $this->toArray();
		$data['definedFeatures'] = [];
		foreach ( $this->product_features_define as $feature ) {
			$data['definedFeatures'][$feature->id] = $feature->toArray();
		}
	}

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->_product_features !== null) {
            if (!$this->_product_features) {
                // Delete all if empty value
                $this->_product_features = [];
            }

            $exists = [];
            foreach ($this->product_features as $product_feature) {
                $exists[$product_feature->product_feature_id] = $product_feature->product_feature_id;
            }

            foreach (array_values($this->_product_features) as $i => $product_feature) {
                if (is_object($product_feature)) {
                    $product_feature = $product_feature->toArray();
                }

                $product_feature[AdminHelper::FIELDNAME_SORT] = $i;
                $product_feature['product_type_id'] = $this->id;

                if (isset($exists[$product_feature['product_feature_id']])) {
                    unset($exists[$product_feature['product_feature_id']]);

                    $link = ProductTypeHasProductFeature::findOne([
                        'product_type_id' => $product_feature['product_type_id'],
                        'product_feature_id' => $product_feature['product_feature_id'],
                    ]);
                    $link->load($product_feature, '');
                } else {
                    $link = new ProductTypeHasProductFeature($product_feature);
                }
                if (!$link->save()) {
                    Yii::$app->session->setFlash('error', SiteHelper::getErrorMessages($link->errors));
                }


            }

            if (count($exists) > 0) {
                ProductTypeHasProductFeature::deleteAll(['product_type_id' => $this->id, 'product_feature_id' => $exists]);
            }
        }
    }

	/**
	 * @return ProductItem
	 */
	protected function getCategoryClass() {
		return Yii::$container->get($this->categoryModelClass);
	}
}
