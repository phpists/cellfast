<?php

namespace noIT\content\models;

use Imagine\Image\ManipulatorInterface;
use noIT\cache\EntityModelTrait;
use noIT\core\behaviors\SlugBehavior;
use noIT\core\helpers\AdminHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use mohorev\file\UploadImageBehavior;
use voskobovich\linker\LinkerBehavior;

/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property string $image_list
 * @property string $slug
 * @property string $teaser
 * @property string $body
 * @property string $seo_h1
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property integer $status
 */
class Content extends \yii\db\ActiveRecord
{
	use EntityModelTrait;

	/**
	 * Entity-model
	 */
	public $entityModel = 'noIT\content\models\ContentEntity';

    const STATUS_DRAFT = -1;
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 10;

    protected $auto_teaser_words = 600;

	public $imagesUploadClass = 'noIT\gallery\models\GalleryImage';
	public $imagesUploadEntity = 'content';
	public $imagesUpload;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%content}}';
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
                'createThumbsOnRequest' => true,
	            'createThumbsOnSave' => true,
                'attribute' => 'image',
                'scenarios' => ['default'],
                'path' => '@cdn/content',
                'url' => '@cdnUrl/content',
                'thumbs' => [
                    'thumb' => ['width' => 200, 'height' => 200],
                    'thumb_list' => ['height' => 50],
                    'thumb_small' => ['width' => 350, 'height' => 290],
                    'thumb_book' => [
	                    'width' => 350,
	                    'height' => 290,
	                    'quality' => 90,
	                    'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND
                    ],
                ],
            ],
            'image_list' => [
                'class' => UploadImageBehavior::className(),
	            'createThumbsOnRequest' => true,
                'createThumbsOnSave' => true,
                'attribute' => 'image_list',
                'scenarios' => ['default'],
                'path' => '@cdn/content',
                'url' => '@cdnUrl/content',
                'thumbs' => [
                    'thumb' => ['width' => 200, 'height' => 200,],
                    'thumb_list' => ['height' => 50],
                    'thumb_book' => [
	                    'width' => 350,
	                    'height' => 215,
	                    'quality' => 90,
	                    'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND
                    ],
                ],
            ],
	        'relations' => [
		        /**  TODO - Если первым идет не PK - то лажа, см. поведение LinkerBehavior или ядро?! */
		        'class' => LinkerBehavior::className(),
		        'relations' => [
			        'rel_tags' => [
				        'tags',
				        'updater' => [
					        'viaTableAttributesValue' => [
						        'updated_at' => function() {
							        return new \yii\db\Expression('NOW()');
						        },
					        ],
				        ]
			        ],
			        'rel_categories' => [
				        'categories',
				        'updater' => [
					        'viaTableAttributesValue' => [
						        'updated_at' => function() {
							        return new \yii\db\Expression('NOW()');
						        },
					        ],
				        ]
			        ],
		        ],
	        ],
        ];

        $behaviors['slug'] = [
            'class' => SlugBehavior::className(),
            'in_attribute' => AdminHelper::getDefaultLangField('name'),
            'out_attribute' => 'slug',
        ];

        foreach (AdminHelper::getLanguages() as $language) {
            $behaviors[AdminHelper::getLangField("seo_h1", $language)] = [
                'class' => SlugBehavior::className(),
                'in_attribute' => AdminHelper::getDefaultLangField('name'),
                'out_attribute' => AdminHelper::getLangField("seo_h1", $language),
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
            [AdminHelper::LangsField(['name']), 'required'],
            [AdminHelper::LangsField(['name', 'seo_h1', 'seo_title']), 'string', 'max' => 150],
            [AdminHelper::LangsField(['teaser', 'body', 'seo_description', 'seo_keywords']), 'string'],
            [['created_at', 'updated_at', AdminHelper::FIELDNAME_STATUS], 'integer'],
            [['slug'], 'string', 'max' => 255],
            [['image', 'image_list'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['default']],
	        [['imagesUpload', AdminHelper::FIELDNAME_PUBLISHED], 'safe'],
	        [[AdminHelper::FIELDNAME_PUBLISHED], 'default', 'value' => NULL],
	        [['rel_tags'], 'each', 'rule' => ['integer']],
	        [['rel_categories'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $result = [
            'id' => 'ID',
            'image' => Yii::t('app', 'Cover'),
            'image_list' => Yii::t('app', 'Cover on list'),
            'images' => Yii::t('app', 'Images'),
            'imagesUpload' => Yii::t('app', 'Images gallery'),
            'slug' => Yii::t('app', 'URL-address'),
	        'rel_tags' => Yii::t('app', 'Relations tags'),
	        'rel_categories' => Yii::t('app', 'Relations categories'),
	        'published_at' => Yii::t('app', 'Published At'),
            'status' => Yii::t('app', 'Status'),
        ];

        foreach (AdminHelper::getLanguages() as $language) {
            $result = array_merge($result, [
                AdminHelper::getLangField('name', $language) => Yii::t('app', 'Title') ." ". Yii::t('app', $language->code),
                AdminHelper::getLangField('teaser', $language) => Yii::t('app', 'Teaser') ." ". Yii::t('app', $language->code),
                AdminHelper::getLangField('body', $language) => Yii::t('app', 'Body') ." ". Yii::t('app', $language->code),
                AdminHelper::getLangField('seo_h1', $language) => Yii::t('app', 'Seo H1') ." ". Yii::t('app', $language->code),
                AdminHelper::getLangField('seo_title', $language) => Yii::t('app', 'Seo Title') ." ". Yii::t('app', $language->code),
                AdminHelper::getLangField('seo_description', $language) => Yii::t('app', 'Seo Description') ." ". Yii::t('app', $language->code),
                AdminHelper::getLangField('seo_keywords', $language) => Yii::t('app', 'Seo Keywords') ." ". Yii::t('app', $language->code),
            ]);
        }

        return $result;
    }

    public function getName()
    {
        return Yii::$app->languages->current->getEntityField($this, 'name') ? : $this->name_ru_ru;
    }

    public function getTeaser()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'teaser');
    }

    public function getBody()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'body');
    }

    public function getH1()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'seo_h1');
    }

    public function getTitle()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'seo_title');
    }

    public function getSeo_description()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'seo_description');
    }

    public function getSeo_keywords()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'seo_keywords');
    }

	// TODO - move images to the trait
	public function getImageList()
	{
    	return $this->image_list ? : $this->image;
	}

	public function getImages()
	{
		return $this->hasMany($this->imagesUploadClass, ['entity_id' => 'id'])
		            ->andWhere(['entity' => $this->imagesUploadEntity])
		            ->orderBy([AdminHelper::FIELDNAME_SORT => SORT_ASC]);
	}

	public function imagesUpload()
	{
		if ($this->imagesUpload && $this->validate()) {
			$sortField = AdminHelper::FIELDNAME_SORT;

			foreach (array_values($this->imagesUpload) as $j => $image) {
				$imageModel = new $this->imagesUploadClass();
				$imageModel->entity = strtolower($this->imagesUploadEntity);
				$imageModel->entity_id = $this->id;

				$baseName = uniqid();
				$imageModel->src = $baseName .'.'. $image->extension;

				$imageModel->$sortField = $j;

				$i = 0;
				while(file_exists($imageModel->file)) {
					$i++;
					$imageModel->src = $baseName .'_'. $i .'.'. $image->extension;
				}

				if (!file_exists($imageModel->imagePath(''))) {
					mkdir($imageModel->imagePath(''), 0777, true);
				}
				if ($image->saveAs($imageModel->file)) {
					if (!$imageModel->save(false, ['entity', 'entity_id', 'src', 'sort_order'])) {}
				}
			}
			$this->imagesUpload = null;
			return true;
		} else {
			return false;
		}
	}

    public function afterFind()
    {
        parent::afterFind();

        if ($this->auto_teaser_words) {
            // Disable autogenerate teaser from body-value
            /*foreach (AdminHelper::getLanguages() as $language) {
                $this->{"teaser_{$language->suffix}"} = $this->{"teaser_{$language->suffix}"} ?: \yii\helpers\StringHelper::truncate($this->{"body_{$language->suffix}"}, $this->auto_teaser_words);
            }*/
        }
    }

    public function beforeSave($insert)
    {
        if (is_null($this->status) || $this->status == '') {
            $this->status = self::STATUS_DRAFT;
        }

        if ($this->status == self::STATUS_ACTIVE && !$this->published_at) {
	        $this->published_at = time();
        }

        return parent::beforeSave($insert);
    }

    public static function getAll() {
        return self::find()->all();
    }
}
