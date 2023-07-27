<?php
namespace common\models;

use common\helpers\AdminHelper;
use common\behaviors\SlugBehavior;
use common\components\projects\Project;
use noIT\cache\EntityModelTrait;
use noIT\feature\models\FeatureGroup;
use Yii;
use yii\helpers\StringHelper;
use Imagine\Image\ManipulatorInterface;
use mohorev\file\UploadImageBehavior;

/**
 * This is the model class for table "product_feature".
 *
 * @property string $value_type
 * @property boolean $multiple
 * @property string $filter_widget
 * @property string $admin_widget
 * @property ProductFeatureValue[] $values
 * @property Project $project
 */

class ProductFeature extends FeatureGroup {
	use EntityModelTrait;

	/**
	 * Entity-model
	 */
	public $entityModel = 'common\models\ProductFeatureEntity';

    protected $valueModelClass = 'common\models\ProductFeatureValue';

    protected $_values;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_feature}}';
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['slug'] = [
            'class' => SlugBehavior::className(),
            'in_attribute' => 'native_name',
            'out_attribute' => 'slug',
        ];

        $behaviors['image'] = [
            'class' => UploadImageBehavior::className(),
            'attribute' => 'image',
            'scenarios' => ['default'],
            'path' => '@root/{project.alias}/web/media/category',
            'url' => '{project.url}media/category',
            'thumbs' => [
                'thumb' => ['width' => 200, 'height' => 200,],
                'thumb_list' => ['height' => 50],
                'catalog_list' => ['width' => 350, 'height' => 301, 'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND],
            ],
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
            [['native_name', 'project_id'], 'required'],
            [['project_id'], 'string', 'max' => 20],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['native_name', 'slug'], 'string', 'max' => 100],
            [['slug'], 'unique', 'targetAttribute' => ['slug', 'project_id']],
            [AdminHelper::LangsField(['name']), 'string', 'max' => 150],
            [AdminHelper::LangsField(['caption']), 'string'],
            [['multiple', 'overall'], 'boolean'],
            [['value_type'], 'string', 'max' => 3],
            [['admin_widget', 'filter_widget'], 'string', 'max' => 255],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['default']],
            [['value'], 'exist', 'skipOnError' => true, 'targetClass' => $this->valueModelClass, 'targetAttribute' => ['group_id' => 'id']],
            [['values'], 'safe'],
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
            'value_type' => Yii::t('app', 'Value type'),
            'overall' => Yii::t('app', 'Overall'),
            'multiple' => Yii::t('app', 'Multiple'),
            'filter_widget' => Yii::t('app', 'Filter widget'),
            'admin_widget' => Yii::t('app', 'Admin widget'),
            'native_name' => Yii::t('app', 'Native Name'),
            'slug' => Yii::t('app', 'Slug'),
            'image' => Yii::t('app', 'Image'),
            'status' => Yii::t('app', 'Status'),
            'values' => Yii::t('app', 'Values'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ],
            AdminHelper::LangsFieldLabels('name', 'Name'),
            AdminHelper::LangsFieldLabels('caption', 'Caption')
        );
    }

    public function getProject() {
        return Yii::$app->projects->getProject($this->project_id);
    }

    public function getName() {
        return \Yii::$app->languages->current->getEntityField($this, 'name');
    }

    public function getCaption($length = 10) {
        return StringHelper::truncateWords($this->description, $length);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValues()
    {
	    return $this->hasMany($this->valueModelClass, ['feature_id' => 'id'])->orderBy([AdminHelper::FIELDNAME_SORT => SORT_ASC]);
    }

    public function setValues($values) {
        $this->_values = $values;
    }

    public function getFilter_widget() {
    	return $this->filter_widget ? : 'default';
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->_values !== null) {
            if (!$this->_values) {
                // Delete all if empty value
                $this->_values = [];
            }

            $todel = $toadd = $toupdate = [];
            foreach ($this->values as $value) {
                $todel[$value->id] = $value->id;
            }

            foreach (array_values($this->_values) as $i => $value) {
                if (is_object($value)) {
                    $value = $value->toArray();
                }
                // Add external relations
                $value = [
                    'feature_id' => $this->id,
                ] + $value;
                $value[AdminHelper::FIELDNAME_SORT] = $i;

                if (!empty($value['id'])) {
                    if (isset($todel[$value['id']])) {
                        unset($todel[$value['id']]);
                    }
                    $toupdate[$value['id']] = $value;
                } elseif (empty($value['id'])) {
                    $toadd[] = $value;
                }
            }

            if (count($todel) > 0) {
                call_user_func([$this->valueModelClass, 'deleteAll'], ['id' => $todel]);
            }

            foreach ($toadd as $value) {
                $_value = Yii::createObject($this->valueModelClass);
                $_value->load($value, '');
                $_value->save();
            }
            foreach ($toupdate as $value) {
                /** @var ProductFeatureValue $_value */
                if ( !($_value = call_user_func([$this->valueModelClass, 'find'])->where(['id' => $value['id']])->one()) ) {
                    continue;
                }
                $_value->load($value, '');
                $_value->save();
	            $this->_values[$value['id']] = null;
            }
        }
    }

    public static function all($project_id = null) {
        $query = self::find();
        if ($project_id) {
            $query->andWhere(['project_id' => $project_id]);
        }
        return $query->all();
    }

    public static function enabled($project_id = null) {
        $query = self::find()
            ->where(['status' => self::STATUS_ACTIVE]);
        if ($project_id) {
            $query->andWhere(['project_id' => $project_id]);
        }
        return $query->all();
    }

	/**
	 * Формирование данных для загрузки в modelEntity
	 */
	protected function prepareEntity() {
		$data = $this->toArray();

		$data['values'] = [];
		foreach ($this->values as $value) {
		    if ($value->value_label === '-') {
		        // Skip dummy options
		        continue;
            }
			$data['values'][] = new ProductFeatureValueEntity($value->toArray(array_merge(
				[
					'id',
					'feature_id',
					'value',
					'slug',
					'sort_order'
				],
				AdminHelper::LangsField([
					'value_label',
				])
			)));
		}

		return $data;
	}
}
