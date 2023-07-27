<?php
namespace noIT\user;

use yii\base\Module;

class UserModule extends Module {
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'noIT\user\controllers';

    public static $defaultRoutes = [
        'user/login' => 'user/default/login',
        'user/logout' => 'user/default/logout',
        'user/register' => 'user/default/signup',
        'user/request-password-reset' => 'user/default/request-password-reset',
        'user/reset-password' => 'user/default/reset-password',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function DefaultRoutes() {
        return self::$defaultRoutes;
    }
}