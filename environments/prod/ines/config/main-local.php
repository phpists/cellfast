<?php
$config = require(__DIR__ . '/main.php');

// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
$config['components']['request']['cookieValidationKey'] = '';

return $config;