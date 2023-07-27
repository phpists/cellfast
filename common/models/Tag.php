<?php

namespace common\models;

use Yii;
use common\helpers\SiteHelper;
use common\components\projects\Project;

/**
 * Class Tag
 * @package common\models
 * @property Project $project
 */
class Tag extends \noIT\feature\models\SingleFeature {
    public static function tableName()
    {
        return '{{%tag}}';
    }

    public function rules()
    {
        return array_merge(
            [[['project_id'], 'string', 'max' => 20]],
            parent::rules()
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            ['project_id' => Yii::t('app', 'Project')],
            parent::attributeLabels()
        );
    }

    public function getProject() {
        return Yii::$app->projects->getProject($this->project_id);
    }

    public static function all($project = null) {
        if (null === $project) {
            $project = SiteHelper::getProject();
        }
        return self::find()->where(['project_id' => $project])->all();
    }

    public static function enabled($project = null) {
        if (null === $project) {
            $project = SiteHelper::getProject();
        }
        return self::find()->where(['project_id' => $project, 'status' => Tag::STATUS_ACTIVE])->all();
    }
}