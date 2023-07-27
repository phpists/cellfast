<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_type_has_product_feature".
 *
 * @property integer $product_type_id
 * @property integer $product_feature_id
 * @property integer $multiple
 * @property boolean $multiple_bool
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $defining
 * @property boolean $defining_bool
 * @property string $admin_widget
 * @property string $filter_widget
 *
 * @property ProductFeature $product_feature
 * @property ProductType $product_type
 * @property boolean $deifining_bool
 */
class ProductTypeHasProductFeature extends \yii\db\ActiveRecord
{
    const DEFINING_ON = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_type_has_product_feature';
    }

    public static function primaryKey()
    {
        return [
            'product_type_id',
            'product_feature_id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_type_id', 'product_feature_id'], 'required'],
            [['product_type_id', 'product_feature_id', 'created_at', 'updated_at'], 'integer'],
            [['multiple', 'defining'], 'boolean'],
            [['admin_widget', 'filter_widget'], 'string', 'max' => 255],
            [['in_filter'], 'boolean'],
            [['sort_order'], 'integer'],
            [['product_type_id', 'product_feature_id'], 'unique', 'targetAttribute' => ['product_type_id', 'product_feature_id'], 'message' => 'The combination of Product Type ID and Product Feature ID has already been taken.'],
            [['product_feature_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductFeature::className(), 'targetAttribute' => ['product_feature_id' => 'id']],
            [['product_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductType::className(), 'targetAttribute' => ['product_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_type_id' => Yii::t('app', 'Product Type ID'),
            'product_feature_id' => Yii::t('app', 'Product Feature ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'defining' => Yii::t('app', 'Defining'),
            'admin_widget' => Yii::t('app', 'Admin Widget'),
            'filter_widget' => Yii::t('app', 'Filter Widget'),
            'in_filter' => Yii::t('app', 'Show on filter'),
            'sort_order' => Yii::t('app', 'Sort on filter'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct_feature()
    {
        return $this->hasOne(ProductFeature::className(), ['id' => 'product_feature_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct_type()
    {
        return $this->hasOne(ProductType::className(), ['id' => 'product_type_id']);
    }

    public function getMultiple_bool() {
        return $this->multiple == 1;
    }

    public function getDefaining_bool() {
        return $this->multiple == 1;
    }
}
