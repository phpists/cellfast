<?php

namespace noIT\soap;

use noIT\soap\components\SoapResponseFormatter;
use yii\base\ErrorException;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\log\DbTarget;
use yii\log\Dispatcher;
use yii\log\FileTarget;
use yii\log\Logger;

/**
 * soap_server module definition class
 * Для подключения модуля к приложению необходимо определить
 * ID модуля из wsdl определения веб-сервиса, а именно из
 * <soap:address location="http://cellfast.ua/webapi"/> (в данном примере ID = webapi)
 * Затем подключить этот модуль, сделав следующюю запись в конфигурационном файле
 *      'modules' => [ ID => ['class' => 'noIT\soap\SoapServerModule']]
 * А также добавить в том же файле маршрут для UrlManager-а
 *      'components' => ['urlManager' => ['rules' => [
 *          '<module:(ID)>' => '<module>']]]
 * Если экземпляров модулей в приложении нужно несколько, то данное правило приобретает вид
 *      'components' => ['urlManager' => ['rules' => [
 *          '<module:(ID_1|...|ID_N)>' => '<module>']]]
 * Также нужно создать конфигурационный файл в каталоге config в иерархии каталогов модуля с названием
 * "{ID_App}_{ID_Module}_params.php" (в нашем примере это будет "cellfast_webapi_params.php"),
 * указав в нем необходимие для работы данного экземпляра модуля настройки.
 */
class SoapServerModule extends Module
{
    /**
     * This constant maps the entity names from 1C to the names of the application classes and tables
     */
    const ENTITY_NAMES_MAP = [
        'groupsOfGoods'             => "e1c_group_of_good",
        'codesUCGFEA'               => "e1c_code_ucgfea",
        'goods'                     => "e1c_good",
        'warehouses'                => "e1c_warehouse",
        'availabilityOfGoods'       => "e1c_availability_of_good",
        'typesOfPrices'             => "e1c_type_of_price",
        'prices'                    => "e1c_price",
        'clients'                   => "e1c_client",
        'agreements'                => "e1c_agreement",
        'requisitesOfAgreements'    => "e1c_requisite_of_agreement",
        'ordersHeads'               => "e1c_head_of_order",
        'ordersGoods'               => "e1c_good_of_order",
        'commentsOfOrders'          => "e1c_comment_of_order",
        'printFormsOfOrders'        => "e1c_print_form_of_order",
        'receivables'               => "e1c_receivable",
    ];

    /**
     * define ID for error handler for SOAP
     */
    const ERROR_HANDLER_ID = "errorHandlerSoap";

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'noIT\soap\controllers';

    /**
     * @var array
     */
    protected static $logLevel = [
        'error' => Logger::LEVEL_ERROR,
        'warn' => Logger::LEVEL_WARNING,
        'info' => Logger::LEVEL_INFO,
    ];

    /**
     * @param string $message
     * @param string $level
     * @param string $category
     */
    public function log($message, $level = "error", $category = "SOAP_info")
    {
        $level = (isset(self::$logLevel[$level])) ? self::$logLevel[$level] : Logger::LEVEL_ERROR;
        $this->get('dispatcher')->logger->log($message, $level, $category);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
//        if (PHP_SAPI === 'cli') {
//            throw new ErrorException("This module is not designed to work in the console");
//        }
        if (!extension_loaded("dom")) {
            throw new ErrorException("The required 'php-xml' extension is not loaded");
        }
        $configDir = __DIR__. DIRECTORY_SEPARATOR . "config";
        $defaultConfig = $configDir . DIRECTORY_SEPARATOR . "default_params.php";
        if (!is_readable($defaultConfig)) {
            throw new ErrorException("Unable to locate default config file");
        }
        $defaultConfig = require $defaultConfig;
        if (!is_array($defaultConfig)) {
            throw new ErrorException("Unable to open default config file or it is corrupted");
        }
        $customConfig = $configDir . DIRECTORY_SEPARATOR . \Yii::$app->id . "_{$this->id}_params.php";
        if (is_readable($customConfig)) {
            $customConfig = require $customConfig;
            if (!is_array($customConfig)) {
                throw new ErrorException("Unable to open custom config file or it is corrupted");
            }
        } else {
            $customConfig = [];
        }
        $customConfig = ArrayHelper::merge($defaultConfig, $customConfig);
        $customConfig['configDir'] = $configDir;
        /**
         * Define default route (can be route to controller or to controller/action)
         */
        $defaultRoute = "";
        if (isset($customConfig['defaultRoute'])) {
            $defaultRoute = $customConfig['defaultRoute'];
            unset($customConfig['defaultRoute']);
        }
        /**
         * Create dispatcher for module
         */
        $serviceDefinition = [
            'class' => Dispatcher::className(),
            'logger' => [
                'class' => Logger::className(),
            ],
            'targets' => [
            ],
        ];
        $serviceDefinition['targets']['logDb'] = [
            'class' => DbTarget::className(),
            'categories' => ["SOAP_error"],
            'logVars' => [],
            'logTable' => "{{%e1c_log}}",
        ];
        if (isset($customConfig['logFile'])) {
            $pathToLogFile = __DIR__ . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . $customConfig['logFile'];
            $serviceDefinition['targets']['logFile'] = [
                'class' => FileTarget::className(),
                'except' => ["SOAP_message", "SOAP_error"],
                'logVars' => ["_GET", "_POST", "_SERVER"],
                'logFile' => $pathToLogFile,
            ];
            unset($customConfig['logFile']);
        }
        if (isset($customConfig['logSoapMessageFile'])) {
            $pathToLogFile = __DIR__ . DIRECTORY_SEPARATOR . "runtime" . DIRECTORY_SEPARATOR . $customConfig['logSoapMessageFile'];
            $serviceDefinition['targets']['logSoapMessageFile'] = [
                'class' => FileTarget::className(),
                'categories' => ["SOAP_message"],
                'logVars' => [],
                'logFile' => $pathToLogFile,
            ];
            unset($customConfig['logSoapMessageFile']);
        }
        $this->set('dispatcher', $serviceDefinition);
        $this->get('dispatcher');
        /**
         * Configure our module
         */
        $customConfig = [
            'defaultRoute' => $defaultRoute,
            'params' => $customConfig
        ];
        \Yii::configure($this, $customConfig);
        ini_set('max_execution_time', 300);
        date_default_timezone_set($this->params['timeZone']);
        ob_start();
        if ($defaultRoute === 'frontend') {
            SoapResponseFormatter::initResponse($this->params['soapContentType']);
        }
        $errorHandler = $this->module->get(self::ERROR_HANDLER_ID, false);
        if ($errorHandler!== null) {
            $errorHandler->register();
        }
        \Yii::$app->i18n->translations['soap'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath'       => '@noIT/soap/messages',
            'fileMap'        => ['soap' => 'translate.php'],
        ];
    }
}