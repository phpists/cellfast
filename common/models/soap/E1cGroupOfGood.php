<?php

namespace common\models\soap;

use common\models\Category;
use common\models\ProductType;
use noIT\soap\models\SoapE1cInterface;
use noIT\soap\models\SoapE1cModel;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%e1c_group_of_good}}".
 *
 * @property integer $id
 * @property string $guid
 * @property string $name
 * @property integer $mark_deleted
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 *
 * @property E1cAgreement[] $e1cAgreementsGuid
 * @property E1cAgreement[] $e1cAgreements
 * @property E1cGood[] $e1cGoodsGuid
 * @property E1cGood[] $e1cGoods
 */
class E1cGroupOfGood extends SoapE1cModel implements SoapE1cInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_group_of_good}}';
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
    public function getE1cAgreementsGuid()
    {
        return $this->hasMany(E1cAgreement::className(), ['group_of_good' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cAgreements()
    {
        return $this->hasMany(E1cAgreement::className(), ['group_of_good_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGoodsGuid()
    {
        return $this->hasMany(E1cGood::className(), ['group_of_good' => 'guid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getE1cGoods()
    {
        return $this->hasMany(E1cGood::className(), ['group_of_good_id' => 'id']);
    }

	public function getAppEntities() {
		return $this->hasMany(ProductType::className(), ['e1c_id' => 'id']);
	}

	public function getAppEntitiesLabel() {
    	return implode('<br>', \yii\helpers\ArrayHelper::getColumn($this->appEntities, 'native_name'));
	}
}