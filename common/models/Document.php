<?php
namespace common\models;

/**
 * This is the model class for table "document".
 *
 * @property int $id
 * @property string $project_id
 * @property string $type
 * @property string $name_ru_ru
 * @property string $name_uk_ua
 * @property string $caption_ru_ru
 * @property string $caption_uk_ua
 * @property string $cover_image
 * @property string $file
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */

use noIT\upload\UploadBehavior;
use Yii;
use common\helpers\AdminHelper;
use mohorev\file\UploadImageBehavior;
use yii\behaviors\TimestampBehavior;
use noIT\upload\UploadsBehavior;
use Imagine\Image\ManipulatorInterface;

class Document extends \yii\db\ActiveRecord
{
	const PAGE_SIZE = 6;

	const DISABLE = 0;
	const ENABLE = 10;

	const TYPE_DOCUMENT = 'document';
	const TYPE_CERTIFICATE = 'certificate';
	const TYPE_PRICE_LIST = 'price-list';
	const TYPE_CATALOG = 'catalog';

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'document';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['project_id', 'type'], 'required'],

			[AdminHelper::LangsField(['name']), 'required'],
			[AdminHelper::LangsField(['name']), 'string', 'max' => 255],
			[AdminHelper::LangsField(['caption']), 'string', 'max' => 255],

			[['status', 'created_at', 'updated_at'], 'integer'],
			[['project_id', 'type'], 'string', 'max' => 255],

			[['cover_image'], 'image',
				'skipOnEmpty' => true,
				'extensions' => 'jpeg, jpg, png',
				'minWidth' => 220,
				'minHeight' => 300
			],

			[['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
		];
	}

	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => TimestampBehavior::className(),
			],
			'imageUploads' => [
				'class' => UploadsBehavior::className(),
				'attributes' => [
					'cover_image' => [
						'class' => UploadImageBehavior::className(),
						'createThumbsOnSave' => true,
						'createThumbsOnRequest' => true,
						'generateNewName' => true,
						'thumbs' => [
							'thumb' => [
								'width' => 220,
								'height' => 300,
								'quality' => 90,
								'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND
							],
							'thumb_book' => [
								'width' => 220,
								'height' => 300,
								'quality' => 90,
								'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND
							],
						],
						'attribute' => 'cover_image',
						'scenarios' => ['default'],
						'path' => '@cdn/documents/{id}',
						'url' => '@cdnUrl/documents/{id}',
					],
					'file' => [
						'class' => UploadBehavior::className(),
						'attribute' => 'file',
						'scenarios' => ['default'],
						'path' => '@cdn/documents/{id}',
						'url' => '@cdnUrl/documents/{id}',
					],
				],
			],
		];
	}

    public function getName()
    {
        return Yii::$app->languages->current->getEntityField($this, 'name') ? : $this->name_ru_ru;
    }

    public function getCaption()
    {
        return Yii::$app->languages->current->getEntityField($this, 'caption') ? : $this->caption_ru_ru;
    }

}
