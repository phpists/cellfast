<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../../common/config/bootstrap-local.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main-local.php')
);

/**
 * For correct operation of the SOAP module, it is necessary to use this preloader.
 * Otherwise, use this call: $app = new yii\web\Application($config);
 */
require(__DIR__ . '/../../noIT/soap/config/preloader.php');
$app->run();

