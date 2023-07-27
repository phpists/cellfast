<?php

namespace common\models\soap;

use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;

/**
 * This is the model class for table "{{%e1c_client}}".
 *
 * @property integer $id
 * @property string $guid
 * @property integer $superior_id
 * @property string $superior
 */
class E1cClientHierarchy extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_client}}';
    }

    /**
     * @inheritdoc
     */
    public static function getTableLayout()
    {
        return [
            'loadableAttributes' => [
                'guid' => self::TYPE_GUID, 'superior' => self::TYPE_GUID
            ],
            'foreignKeys' => [
                'superior' => [
                    'class' => E1cClient::className(),
                    'targetField' => 'superior_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT
                ],
            ],
            'updatable' => true,
            'primaryKey' => ['guid' => self::TYPE_GUID],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cClientGuid()
    {
        return $this->hasOne(E1cClient::className(), ['guid' => 'superior']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cClient()
    {
        return $this->hasOne(E1cClient::className(), ['id' => 'superior_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cClientsGuid()
    {
        return $this->hasMany(E1cClient::className(), ['superior' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cClients()
    {
        return $this->hasMany(E1cClient::className(), ['superior_id' => 'id']);
    }
}