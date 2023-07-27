<?php

namespace common\models;

use common\helpers\AdminHelper;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product_property".
 * TODO - оформить в виде отдельного модуля
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $name_ru_ru
 * @property string $name_uk_ua
 * @property string $value_ru_ru
 * @property string $value_uk_ua
 * @property integer $sort_order
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Product $product
 */
class ProductProperty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_property}}';
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
		];

		return $behaviors;
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        [['product_id'], 'required'],
	        [['product_id', 'sort_order', 'created_at', 'updated_at'], 'integer'],
	        [AdminHelper::LangsField(['value']), 'string'],
	        [AdminHelper::LangsField(['name']), 'string', 'max' => 150],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
	    $result = [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
            AdminHelper::FIELDNAME_SORT => Yii::t('app', 'Sort Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];

	    foreach (AdminHelper::getLanguages() as $language) {
		    $result = array_merge($result, [
			    AdminHelper::getLangField('name', $language) => Yii::t('app', 'Name') ." ". Yii::t('app', $language->code),
			    AdminHelper::getLangField('value', $language) => Yii::t('app', 'Value') ." ". Yii::t('app', $language->code),
		    ]);
	    }

	    return $result;
    }

	public function getName() {
		return \Yii::$app->languages->current->getEntityField($this, 'name');
	}

	public function getValue() {
		return \Yii::$app->languages->current->getEntityField($this, 'value');
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
