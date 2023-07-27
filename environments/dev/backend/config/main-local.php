<?php

$allowedIPs = [
    '127.0.0.1', '::1'
];

$config = require(__DIR__ . '/main.php');

// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
$config['components']['request']['cookieValidationKey'] = '';

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => $allowedIPs,
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => $allowedIPs,
    ];
}

return $config;
