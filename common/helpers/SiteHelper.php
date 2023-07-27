<?php

namespace common\helpers;

class SiteHelper extends \noIT\core\helpers\SiteHelper
{
    public static function getProject()
    {
        return \Yii::$app->id;
    }
}