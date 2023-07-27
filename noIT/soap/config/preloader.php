<?php
/**
 * This preloader is needed in order to be the first in the call stack function 'register_shutdown_function()'
 */

$errorHandler = new \noIT\soap\components\ErrorHandlerComponent();
$app = new yii\web\Application($config);
$app->set(\noIT\soap\SoapServerModule::ERROR_HANDLER_ID, $errorHandler);