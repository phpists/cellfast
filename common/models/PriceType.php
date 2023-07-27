<?php

namespace common\models;

use common\behaviors\SlugBehavior;
use common\helpers\AdminHelper;
use common\models\soap\E1cTypeOfPrice;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%price_type}}".
 *
 * @property integer $id
 * @property string $native_name
 * @property integer $includeVAT
 * @property string $name
 * @property integer $sort_order
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ProductPrice[] $productPrices
 */
class PriceType extends \yii\db\ActiveRecord
{
	/**
	 * Related models
	 */
	public $productPriceModelClass = 'common\models\ProductPrice';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%price_type}}';
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
	        [['native_name'], 'required'],
	        [['includeVAT', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['native_name'], 'string', 'max' => 150],
	        [AdminHelper::LangsField(['name']), 'string', 'max' => 150],
            [['e1c_id'], 'exist', 'skipOnError' => true, 'targetClass' => E1cTypeOfPrice::className(), 'targetAttribute' => ['e1c_id' => 'id']],
        ];
    }

	public function getName() {
		return \Yii::$app->languages->current->getEntityField($this, 'name');
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPrices()
    {
        return $this->hasMany($this->productPriceModelClass, ['price_type_id' => 'id']);
    }

    /**
     * @return ProductPrice[]
     */
    public static function getPriceTypes()
    {
        return static::find()->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cEntity() {
        return $this->hasOne(E1cTypeOfPrice::className(), ['id' => 'e1c_id']);
    }

	public static function getDefault($asObject = true) {
		return $asObject ? self::findOne(Yii::$app->params['defaultPriceType']) : Yii::$app->params['defaultPriceType'];
	}
}
