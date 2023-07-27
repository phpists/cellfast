<?php
return array_merge(
    [
        '/' => 'order/index',
    ],
    \noIT\user\UserModule::DefaultRoutes(),
    ['<module:(webapi)>' => '<module>'],
    [
        'user/login' => 'user/login',
        'user/logout' => 'user/logout',
        'user/request-password-reset' => 'user/request-password-reset',
        'user/reset-password' => 'user/reset-password',
    ],
    [
        'e1c-binding' => 'e1c-binding/index',
    ],
    [
        'import' => 'import/upload',
    ],
    \noIT\core\helpers\SiteHelper::defaultRoutes()
);
