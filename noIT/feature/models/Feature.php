<?php

namespace noIT\feature\models;

/**
 * This is the model class for table "feature".
 *
 * @property integer $group_id
 * @property FeatureGroup $group
 */
class Feature extends SingleFeature
{
    public static function tableName()
    {
        return "{{%feature}}";
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['group_id'], 'required'],
                [['group_id'], 'integer'],
                [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => FeatureGroup::className(), 'targetAttribute' => ['group_id' => 'id']],
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(FeatureGroup::className(), ['id' => 'group_id']);
    }
}
