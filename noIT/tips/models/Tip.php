<?php

namespace noIT\tips\models;

use Yii;

/**
 * This is the model class for table "tips".
 *
 * @property int $id
 * @property string $model
 * @property string $attribute
 * @property string $body
 */
class Tip extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'attribute'], 'required'],
            [['body'], 'string'],
            [['model', 'attribute'], 'string', 'max' => 255],
	        [['model', 'attribute'], 'unique', 'targetAttribute' => ['model', 'attribute']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Модель',
            'attribute' => 'Атрибут',
            'body' => 'Описание подсказки',
        ];
    }

}
