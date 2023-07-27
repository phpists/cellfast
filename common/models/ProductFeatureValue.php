<?php
namespace common\models;

use noIT\cache\EntityModelTrait;
use noIT\feature\models\Feature;
use Yii;
use common\helpers\AdminHelper;
use common\behaviors\SlugBehavior;
use yii\db\Query;

/**
 * This is the model class for table "product_feature_value".
 *
 * @property integer $id
 * @property integer $feature_id
 * @property string $value_label
 * @property string $value_type
 * @property mixed $value
 * @property string $slug
 * @property integer $sort_order
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ProductFeature $group
 */

class ProductFeatureValue extends Feature {
	use EntityModelTrait;

	protected $entityModel = 'common\models\ProductFeatureValueEntity';

	private $_value;

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_feature_value}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['slug'] = [
            'class' => SlugBehavior::className(),
            'in_attribute' => 'value',
            'out_attribute' => 'slug',
        ];

        foreach (Yii::$app->languages->languages as $language) {
            $behaviors[AdminHelper::getLangField('value_label', $language)] = [
                'class' => SlugBehavior::className(),
                'in_attribute' => 'value',
                'out_attribute' => AdminHelper::getLangField('value_label', $language),
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
            [['feature_id'], 'required'],
            [AdminHelper::LangsField(['value_label']), 'string', 'max' => 150],
            [['feature_id', 'value_int', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['value_txt', 'value_obj'], 'string'],
            [['value_flt'], 'number'],
            [['value_str'], 'string', 'max' => 150],
            [['slug'], 'string', 'max' => 255],
            /** TODO - сценарии по тима value для валидации */
            [['value'], 'string', 'max' => 150],
            [['feature_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductFeature::className(), 'targetAttribute' => ['feature_id' => 'id']],
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
            'value' => Yii::t('app', 'Value'),
            'value_label' => Yii::t('app', 'Label'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ],
            AdminHelper::LangsFieldLabels('name', 'Name'),
            AdminHelper::LangsFieldLabels('caption', 'Caption')
        );
    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function getProject() {
        return $this->group->project;
    }

    public function getValue_label() {
        return \Yii::$app->languages->current->getEntityField($this, 'value_label');
    }

    public function getValue_type() {
        return $this->group->value_type;
    }

    public function getValue() {
        if (null === $this->_value) {
            $this->_value = $this->getAttribute("value_{$this->value_type}");
        }
        return $this->_value;
    }

    public function setValue($value) {
        return $this->setAttribute("value_{$this->value_type}", $value);
    }

    public function fields() {
	    return array_merge(
	    	parent::fields(),
		    [
		    	'value' => function() {
	    		    return $this->value;
			    }
		    ]
	    );
    }

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
    	return $this->hasOne(ProductFeature::className(), ['id' => 'feature_id']);
    }

	public function beforeSave($insert)
	{
		return parent::beforeSave($insert);
	}

	/**
	 * @param array $products
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public static function getByProducts($products = []) {
    	$query = (new Query())
		    ->from('{{%product_has_product_feature_value}}')
		    ->select('product_feature_value_id');
    	if ( $products ) {
		    $query->where(['product_id' => $products]);
	    }
		return $query->column();
	}
}