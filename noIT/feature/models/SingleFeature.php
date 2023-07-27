<?php

namespace noIT\feature\models;

use Yii;
use noIT\core\behaviors\SlugBehavior;
use noIT\core\helpers\AdminHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $name
 * @property string $image
 * @property string $slug
 * @property string $caption
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class SingleFeature extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = -1;
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 10;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
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

        $behaviors[] = [
            'class' => SlugBehavior::className(),
            /** @todo Set default language */
            'in_attribute' => AdminHelper::getLangField('name'),
            'out_attribute' => 'slug',
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [AdminHelper::LangsField(['name']), 'required'],
            [AdminHelper::LangsField(['name']), 'string', 'max' => 150],
            [AdminHelper::LangsField(['caption']), 'string'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $result = [
            'id' => 'ID',
            'slug' => 'Адрес страницы',

            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
            'status' => 'Статус',
        ];

        foreach (AdminHelper::getLanguages() as $language) {
            $result = array_merge($result, [
                AdminHelper::getLangField('name', $language) => Yii::t('app', 'Title') ." ". Yii::t('app', $language->code),
                AdminHelper::getLangField('caption', $language) => Yii::t('app', 'Caption') ." ". Yii::t('app', $language->code),
            ]);
        }
        
        return $result;
    }

    public function getName() {
        return \Yii::$app->languages->current->getEntityField($this, 'name');
    }

    public function getCaption() {
        return \Yii::$app->languages->current->getEntityField($this, 'caption');
    }

    public function beforeSave($insert)
    {
        if ( $this->hasAttribute('status') && ( is_null($this->status) || $this->status == '' ) ) {
            $this->status = 0;
        }

        return parent::beforeSave($insert);
    }

    public static function all() {
        return self::find()->all();
    }

    public static function enabled() {
        return self::find()->where(['status' => SingleFeature::STATUS_ACTIVE])->all();
    }
}
