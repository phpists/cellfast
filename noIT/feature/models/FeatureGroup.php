<?php

namespace noIT\feature\models;

use Yii;
use yii\db\ActiveRecord;
use noIT\core\helpers\AdminHelper;
use noIT\core\behaviors\SlugBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "feature_group".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $name
 * @property string $image
 * @property string $slug
 * @property string $caption
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property integer $group
 */
class FeatureGroup extends ActiveRecord
{
    const STATUS_DRAFT = -1;
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 10;

    public static function tableName()
    {
        return "{{%feature_group}}";
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
            'slug' => [
                'class' => SlugBehavior::className(),
                /** @todo Set default language */
                'in_attribute' => AdminHelper::getLangField('name'),
                'out_attribute' => 'slug',
            ]
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
        if (is_null($this->status) || $this->status == '') {
            $this->status = 0;
        }

        return parent::beforeSave($insert);
    }

	public static function findById($id)
	{
		if ( is_array($id) ) {
			$id = (int)$id['id'];
		}

		if ( empty($id) ) {
			return null;
		}

		if (false !== $cache = Yii::$app->entityCache->getByEntity(self::className(), $id)) {
			return $cache;
		}

		$cache = static::find()
		               ->where(['id' => $id])
		               ->with([
			               'values',
		               ])
		               ->one();

//		Yii::$app->entityCache->set($cache);

		return $cache;
	}

    public static function all() {
        $query = self::find();
        return $query->all();
    }

    public static function enabled() {
        return self::find()->where(['status' => self::STATUS_ACTIVE])->all();
    }
}
