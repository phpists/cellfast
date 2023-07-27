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
 * @property string $name
 * @property string $code_itn
 * @property string $address
 * @property integer $mark_deleted
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cAgreement[] $e1cAgreementsGuid
 * @property E1cAgreement[] $e1cAgreements
 * @property E1cClient $e1cClientGuid
 * @property E1cClient $e1cClient
 * @property E1cClient[] $e1cClientsGuid
 * @property E1cClient[] $e1cClients
 * @property E1cReceivable[] $e1cReceivablesGuid
 * @property E1cReceivable[] $e1cReceivables
 */
class E1cClient extends SoapE1cModel implements SoapE1cInterface
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
                'guid' => self::TYPE_GUID, 'superior' => self::TYPE_GUID, 'name' => self::TYPE_STRING,
                'code_itn' => self::TYPE_STRING, 'address' => self::TYPE_STRING, 'mark_deleted' => self::TYPE_BOOLEAN,
                'timestamp' => self::TYPE_TIMESTAMP
            ],
            'foreignKeys' => [
                'superior' => [
                    'class' => E1cClient::className(),
                    'targetField' => 'superior_id', 'refField' => 'id', 'searchField' => 'guid',
                    'type' => self::FK_STRICT_NULL
                ],
            ],
            'updatable' => true,
            'primaryKey' => ['guid' => self::TYPE_GUID],
            'hierarchical' => ['class' => E1cClientHierarchy::className(), 'field' => 'superior']
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
            'superior_id' => Yii::t('app', 'Superior ID'),
            'superior' => Yii::t('app', 'Superior'),
            'name' => Yii::t('app', 'Name'),
            'code_itn' => Yii::t('app', 'Code Itn'),
            'address' => Yii::t('app', 'Address'),
            'mark_deleted' => Yii::t('app', 'Mark Deleted'),
            'timestamp' => Yii::t('app', 'Timestamp'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cAgreementsGuid()
    {
        return $this->hasMany(E1cAgreement::className(), ['client' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cAgreements()
    {
        return $this->hasMany(E1cAgreement::className(), ['client_id' => 'id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cReceivablesGuid()
    {
        return $this->hasMany(E1cReceivable::className(), ['client' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cReceivables()
    {
        return $this->hasMany(E1cReceivable::className(), ['client_id' => 'id']);
    }
}