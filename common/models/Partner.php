<?php
namespace common\models;

/**
 * This is the model class for table "partner".
 *
 * @property int $id
 * @property string $projects
 * @property string $type
 * @property int $location_region_id
 * @property string $name_ru_ru
 * @property string $name_uk_ua
 * @property string $caption_ru_ru
 * @property string $caption_uk_ua
 * @property string $address
 * @property string $address_ru_ru
 * @property string $address_uk_ua
 * @property string $coordinate
 * @property string $phones
 * @property string $website
 * @property string $logotype
 * @property string $email
 * @property int $status
 */

use Yii;
use mohorev\file\UploadImageBehavior;
use common\helpers\AdminHelper;
use yii\behaviors\TimestampBehavior;
use noIT\core\behaviors\SerializedAttributes;
use noIT\upload\UploadsBehavior;
use Imagine\Image\ManipulatorInterface;
use yii\helpers\ArrayHelper;


class Partner extends \yii\db\ActiveRecord
{
	const DISABLE = 0;
	const ENABLE = 10;

	const TYPE_ONLINE = 'online';
	const TYPE_OFFLINE = 'offline';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'partner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['location_region_id', 'status'], 'integer'],
            [['projects', 'phones'], 'safe'],
            [['type', 'coordinate', 'website', 'email'], 'string', 'max' => 255],
	        [AdminHelper::LangsField(['name']), 'string'],
	        [AdminHelper::LangsField(['caption']), 'string'],
	        [AdminHelper::LangsField(['address']), 'string'],
	        [['logotype'], 'image', 'extensions' => 'jpeg, jpg, png'],
	        [['logotype'], 'image', 'skipOnEmpty' => true, 'on' => ['default']],
	        [['logotype'], 'image', 'skipOnEmpty' => true, 'on' => ['update']],
        ];
    }

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => TimestampBehavior::className(),
			],

			'serializedAttributes' => [
				'class' => SerializedAttributes::className(),
				'attributes' => ['phones'],
			],
			'imageUploads' => [
				'class' => UploadsBehavior::className(),
				'attributes' => [
					'logotype' => [
						'class' => UploadImageBehavior::className(),
						'createThumbsOnSave' => true,
						'createThumbsOnRequest' => true,
						'generateNewName' => true,
						'thumbs' => [
							'thumb' => [
								'width' => 92,
								'height' => 92,
								'quality' => 90,
								'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND
							],
						],
						'attribute' => 'logotype',
						'scenarios' => ['default', 'update'],
						'path' => '@cdn/partner/{id}',
						'url' => '@cdnUrl/partner/{id}',
					],
				],
			],
		];
	}

    public function getName() {
        return \Yii::$app->languages->current->getEntityField($this, 'name');
    }

    public function getCaption() {
        return \Yii::$app->languages->current->getEntityField($this, 'caption');
    }

    public function getAddress() {
        return \Yii::$app->languages->current->getEntityField($this, 'address');
    }

	public function afterFind()
	{
		if(!empty($this->projects)) {

			$projects = explode('|', $this->projects);

			$this->projects = $projects;
		}

		parent::afterFind();
	}

	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {

			if( ($projects = $this->projects) ) {
				$this->projects = implode('|',$projects);
			}

			return true;
		} else {
			return false;
		}
	}

	public function getLocationRegion()
	{
		return $this->hasOne(LocationRegion::className(), ['id' => 'location_region_id']);
	}
}

