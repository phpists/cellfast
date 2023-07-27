<?php

namespace noIT\soap\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

/**
 * This is the model class for table "{{%e1c_session}}".
 *
 * @property integer $id
 * @property string $guid
 * @property string $entity
 * @property integer $size
 * @property string $status
 * @property integer $duration
 * @property string $timestamp
 * @property string $created_at
 * @property string $updated_at
 */
class SoapE1cSessionLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%e1c_session}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('soap', 'ID'),
            'guid' => Yii::t('soap', 'Guid'),
            'entity' => Yii::t('soap', 'Entity'),
            'size' => Yii::t('soap', 'Size'),
            'status' => Yii::t('soap', 'Status'),
            'duration' => Yii::t('soap', 'Duration'),
            'timestamp' => Yii::t('soap', 'Timestamp'),
            'created_at' => Yii::t('soap', 'Created At'),
            'updated_at' => Yii::t('soap', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function list($params)
    {
        self::getDb()->createCommand(
            'CREATE OR REPLACE VIEW `temp` AS SELECT UuidFromBin(`guid`) AS `guid`, 
                SUM(CASE WHEN `status` = \'F\' THEN 1 ELSE 0 END) AS `isFall`, 
                SUM(CASE WHEN `status` = \'W\' THEN 1 ELSE 0 END) AS `isWait`, 
                SUM(CASE WHEN `status` = \'E\' THEN 1 ELSE 0 END) AS `isEnd`, 
                MIN(`created_at`) AS `timestamp` FROM `e1c_session` GROUP BY `guid`')
            ->execute();
        $query = self::find();
        $query->sql = '
            SELECT `guid`, 
            (CASE WHEN `isFall` > 0 THEN \'F\' ELSE (CASE WHEN `isWait` > 0 THEN \'W\' ELSE \'E\' END) END) AS `status`, 
            `timestamp` FROM `temp` ORDER BY UNIX_TIMESTAMP(`timestamp`) DESC';

        $urlManager = new class {
            public function createUrl($params) {return '';}
            public function createAbsoluteUrl($params, $scheme = null) {return '';}
        };
        $sort = new Sort(
            [
                'attributes' => [
                    'timestamp'
                ],
                'defaultOrder' => [
                    'timestamp' => SORT_DESC
                ],
                'sortParam' => '',
                'urlManager' => $urlManager,
            ]
        );

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
        ]);

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public static function view($guid, $params)
    {
        self::getDb()->createCommand(
            'CREATE OR REPLACE VIEW `temp` AS SELECT UuidFromBin(`guid`) AS `guid`, 
                SUM(CASE WHEN `status` = \'F\' THEN 1 ELSE 0 END) AS `isFall`, 
                SUM(CASE WHEN `status` = \'W\' THEN 1 ELSE 0 END) AS `isWait`, 
                SUM(CASE WHEN `status` = \'E\' THEN 1 ELSE 0 END) AS `isEnd`, 
                MIN(`created_at`) AS `timestamp` FROM `e1c_session` GROUP BY `guid`')
            ->execute();
        $query = self::find();
        $query->sql = '
            SELECT `guid`, 
            (CASE WHEN `isFall` > 0 THEN \'F\' ELSE (CASE WHEN `isWait` > 0 THEN \'W\' ELSE \'E\' END) END) AS `status`, 
            `timestamp` FROM `temp` ORDER BY UNIX_TIMESTAMP(`timestamp`) DESC';

        $urlManager = new class {
            public function createUrl($params) {return '';}
            public function createAbsoluteUrl($params, $scheme = null) {return '';}
        };
        $sort = new Sort(
            [
                'attributes' => [
                    'timestamp'
                ],
                'defaultOrder' => [
                    'timestamp' => SORT_DESC
                ],
                'sortParam' => '',
                'urlManager' => $urlManager,
            ]
        );

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
        ]);

        return $dataProvider;
    }
}