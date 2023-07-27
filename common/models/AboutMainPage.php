<?php

namespace common\models;

use Imagine\Image\ManipulatorInterface;
use mohorev\file\UploadImageBehavior;
use noIT\upload\UploadsBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "about_main_page".
 *
 * @property int $id
 * @property string $project_id
 * @property string $name_ru_ru
 * @property string $name_uk_ua
 * @property string $cover
 * @property string $body_ru_ru
 * @property string $body_uk_ua
 * @property string $info_image_1
 * @property string $info_image_2
 * @property string $info_teaser_1_ru_ru
 * @property string $info_teaser_1_uk_ua
 * @property string $info_teaser_2_ru_ru
 * @property string $info_teaser_2_uk_ua
 * @property int $created_at
 * @property int $updated_at
 */

class AboutMainPage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'about_main_page';
    }

	public function behaviors() {
		return [
			'imageUploads' => [
				'class' => UploadsBehavior::className(),
				'attributes' => [
					'cover' => [
						'class' => UploadImageBehavior::className(),
						'createThumbsOnSave' => true,
						'createThumbsOnRequest' => true,
						'generateNewName' => true,
						'thumbs' => [
							'thumb' => [
								'width' => 1110,
								'height' => 240,
								'quality' => 90,
								'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND
							],
						],
						'attribute' => 'cover',
						'scenarios' => ['default'],
						'path' => '@cdn/about-us-main-page',
						'url' => '@cdnUrl/about-us-main-page',
					],
					'info_image_1' => [
						'class' => UploadImageBehavior::className(),
						'createThumbsOnSave' => true,
						'createThumbsOnRequest' => true,
						'generateNewName' => true,
						'thumbs' => [
							'thumb' => [
								'width' => 555,
								'height' => 340,
								'quality' => 90,
								'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND
							],
						],
						'attribute' => 'info_image_1',
						'scenarios' => ['default'],
						'path' => '@cdn/about-us-main-page',
						'url' => '@cdnUrl/about-us-main-page',
					],
					'info_image_2' => [
						'class' => UploadImageBehavior::className(),
						'createThumbsOnSave' => true,
						'createThumbsOnRequest' => true,
						'generateNewName' => true,
						'thumbs' => [
							'thumb' => [
								'width' => 555,
								'height' => 340,
								'quality' => 90,
								'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND
							],
						],
						'attribute' => 'info_image_2',
						'scenarios' => ['default'],
						'path' => '@cdn/about-us-main-page',
						'url' => '@cdnUrl/about-us-main-page',
					],
				],
			],
			'timestamp' => [
				'class' => TimestampBehavior::className(),
			],
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['body_ru_ru', 'body_uk_ua', 'info_teaser_1_ru_ru', 'info_teaser_1_uk_ua', 'info_teaser_2_ru_ru', 'info_teaser_2_uk_ua'], 'string'],
            [['name_ru_ru'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['project_id'], 'string', 'max' => 20],
            [['name_ru_ru', 'name_uk_ua'], 'string', 'max' => 255],
	        [['cover', 'info_image_1', 'info_image_2'], 'image', 'skipOnEmpty' => true, 'extensions' => 'jpeg, jpg, png', 'on' => ['default']],
        ];
    }

	public function getName()
	{
		return \Yii::$app->languages->current->getEntityField($this, 'name');
	}

	public function getBody()
	{
		return \Yii::$app->languages->current->getEntityField($this, 'body');
	}

	public function getInfoTeaser_1()
	{
		return \Yii::$app->languages->current->getEntityField($this, 'info_teaser_1');
	}

	public function getInfoTeaser_2()
	{
		return \Yii::$app->languages->current->getEntityField($this, 'info_teaser_2');
	}
}
