<?php
namespace noIT\core\helpers;

use Yii;

class SiteHelper {
    /**
     * @param string $string
     * @return string
     */
    public static function createdByFull($string = 'Created by <a href="{link}" target="_blank" rel="nofollow">&quot;{name}&quot;</a>') {
        return Yii::t('app', $string, ['link' => 'http://noit-group.com.ua/', 'name' => self::createdBy()]);
    }

    /**
     * @return string
     */
    public static function createdBy() {
        return "noIT Group";
    }

    public static function getUser($field = null) {
        if (null === ($user = Yii::$app->user->identity)) {
            return null;
        }
        return null == $field ? $user : $user->$field;
    }

    public static function defaultRoutes() {
        return [
            '<controller>/<id:\d+>/<action>' => '<controller>/<action>',
            '<controller>/<id:\d+>' => '<controller>/view',
            '<controller>' => '<controller>/index',
            '<module:\w+>/<controller:\w+>/<action:(\w|-)+>' => '<module>/<controller>/<action>',
            '<module:\w+>/<controller:\w+>/<action:(\w|-)+>/<id:\d+>' => '<module>/<controller>/<action>',
        ];
    }

    public static function getErrorMessages($errors, $asArray = true) {
        $messages = [];
        foreach($errors as $errors) {
            $messages[] = implode("\n", $errors);
        }
        return $asArray ? $messages : implode("<br>\n", $messages);
    }
}