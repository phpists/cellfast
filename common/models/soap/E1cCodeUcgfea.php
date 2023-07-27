<?php

namespace common\models\soap;

use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;

/**
 * This is the model class for table "{{%e1c_code_ucgfea}}".
 *
 * @property integer $id
 * @property string $guid
 * @property string $name
 * @property integer $mark_deleted
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cGood[] $e1cGoodsGuid
 * @property E1cGood[] $e1cGoods
 */
class E1cCodeUcgfea extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_code_ucgfea}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'guid' => self::TYPE_GUID, 'name' => self::TYPE_STRING, 'mark_deleted' => self::TYPE_BOOLEAN,
                'timestamp' => self::TYPE_TIMESTAMP
            ],
            'foreignKeys' => [],
            'updatable' => true,
            'primaryKey' => ['guid' => self::TYPE_GUID],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'guid' => Yii::t('app', 'Guid'),
            'name' => Yii::t('app', 'Name'),
            'mark_deleted' => Yii::t('app', 'Mark Deleted'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGoodsGuid()
    {
        return $this->hasMany(E1cGood::className(), ['code_ucgfea' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGoods()
    {
        return $this->hasMany(E1cGood::className(), ['code_ucgfea_id' => 'id']);
    }
}