<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use mohorev\file\UploadImageBehavior;
use noIT\upload\UploadsBehavior;
use Imagine\Image\ManipulatorInterface;

/**
 * Class AboutUs
 *
 * @property int $id
 * @property string $project_id
 * @property string $name_ru_ru
 * @property string $name_uk_ua
 * @property string $slug
 * @property string $cover
 * @property string $cover_2
 * @property string $teaser_ru_ru
 * @property string $teaser_uk_ua
 * @property string $teaser_2_ru_ru
 * @property string $teaser_2_uk_ua
 * @property string $teaser_3_ru_ru
 * @property string $teaser_3_uk_ua
 *
 * @property string $body_ru_ru
 * @property string $body_uk_ua
 * @property string $video
 * @property string $body_2_ru_ru
 * @property string $body_2_uk_ua
 * @property int $created_at
 * @property int $updated_at
 */

class AboutUs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'about_us';
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
                                'width' => 555,
                                'height' => 340,
                                'quality' => 90,
                                'mode' => ManipulatorInterface::THUMBNAIL_OUTBOUND
                            ],
                        ],
                        'attribute' => 'cover',
                        'scenarios' => ['default'],
                        'path' => '@cdn/about-us',
                        'url' => '@cdnUrl/about-us',
                    ],
                    'cover_2' => [
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
                        'attribute' => 'cover_2',
                        'scenarios' => ['default'],
                        'path' => '@cdn/about-us',
                        'url' => '@cdnUrl/about-us',
                    ],
                ],
            ],
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name_ru_ru',
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
            [[
                'teaser_ru_ru', 'teaser_uk_ua',
                'teaser_2_ru_ru', 'teaser_2_uk_ua',
                'teaser_3_ru_ru', 'teaser_3_uk_ua',
                'body_ru_ru', 'body_uk_ua',
                'body_2_ru_ru', 'body_2_uk_ua'
            ], 'string'],

            [['name_ru_ru',], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['project_id'], 'string', 'max' => 20],
            [['name_ru_ru', 'name_uk_ua', 'slug', 'video'], 'string', 'max' => 255],

            [['cover', 'cover_2'], 'image', 'skipOnEmpty' => true, 'extensions' => 'jpeg, jpg, png', 'on' => ['default']],
        ];
    }

    public function getName()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'name');
    }

    public function getTeaser()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'teaser');
    }

    public function getTeaser_2()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'teaser_2');
    }

    public function getTeaser_3()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'teaser_3');
    }

    public function getBody()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'body');
    }

    public function getBody_2()
    {
        return \Yii::$app->languages->current->getEntityField($this, 'body_2');
    }
}
