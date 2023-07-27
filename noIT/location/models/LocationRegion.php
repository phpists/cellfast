<?php

namespace noIT\location\models;

use common\models\Partner;
use mohorev\file\UploadImageBehavior;
use noIT\core\behaviors\SlugBehavior;
use noIT\core\helpers\AdminHelper;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%location_region}}".
 *
 * @property integer $id
 * @property integer $country_id
 * @property string $native_name
 * @property string $image
 * @property string $name_ru_ru
 * @property string $name_uk_ua
 * @property string $body_ru_ru
 * @property string $body_uk_ua
 * @property integer $sort_order
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property LocationPlace[] $places
 * @property LocationCountry $country
 */
class LocationRegion extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 10;
	const STATUS_DISABLED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%location_region}}';
    }

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		$behaviors = [
			[
				'class' => TimestampBehavior::className(),
			],
		];

		$behaviors['image'] = [
			'class' => UploadImageBehavior::className(),
			'attribute' => 'image',
			'scenarios' => ['default'],
			'path' => '@cdn/content',
			'url' => '@cdnUrl/content',
			'thumbs' => [
				'thumb' => ['width' => 200, 'height' => 200,],
				'thumb_list' => ['height' => 50],
			],
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

		return $behaviors;
	}


	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'native_name'], 'required'],
            [['country_id', 'sort_order', 'status', 'created_at', 'updated_at'], 'integer'],
	        [['sort_order'], 'default', 'value'=> 0],
            [['native_name'], 'string', 'max' => 150],
	        [AdminHelper::LangsField(['name']), 'string', 'max' => 150],
	        [AdminHelper::LangsField(['body']), 'string'],
            [['image'], 'string', 'max' => 250],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
	    $result = [
            'id' => Yii::t('app', 'ID'),
            'country_id' => Yii::t('app', 'Country'),
            'native_name' => Yii::t('app', 'Native Name'),
            'image' => Yii::t('app', 'Image'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];

	    foreach (AdminHelper::getLanguages() as $language) {
		    $result = array_merge($result, [
			    AdminHelper::getLangField('name', $language) => Yii::t('app', 'Title') ." ". Yii::t('app', $language->code),
			    AdminHelper::getLangField('body', $language) => Yii::t('app', 'Body') ." ". Yii::t('app', $language->code),
		    ]);
	    }

	    return $result;
    }

	public function getName() {
		return \Yii::$app->languages->current->getEntityField($this, 'name');
	}

	public function getBody() {
		return \Yii::$app->languages->current->getEntityField($this, 'body');
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(LocationPlace::className(), ['region_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(LocationCountry::className(), ['id' => 'country_id']);
    }

    public function getPartner()
    {
    	return $this->hasMany(Partner::className(), ['location_region_id' => 'id']);
    }
}
