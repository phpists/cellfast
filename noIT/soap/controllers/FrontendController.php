<?php

namespace noIT\soap\controllers;

use noIT\soap\actions\GetDataAction;
use noIT\soap\actions\SaveDataAction;
use noIT\soap\actions\ShowWsdlAction;
use noIT\soap\actions\StartSyncAction;
use noIT\soap\components\SoapRequestFormatter;
use noIT\soap\SoapServerModule as SOAP;
use noIT\user\models\User;
use yii\base\ErrorException;
use yii\filters\auth\HttpBasicAuth;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;

/**
 * Frontend controller for the `soap_server` module
 */
class FrontendController extends Controller
{
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    public function behaviors()
    {
        if (\Yii::$app->request->isGet && !isset($_SERVER['PHP_AUTH_USER'])) {
            $_SERVER['PHP_AUTH_USER'] = \Yii::$app->request->getQueryParam("token");
        }
        $behaviors = parent::behaviors();
        $behaviors['basicAuth'] = [
            'class' => HttpBasicAuth::className(),
            'realm' => "webapi",
            'auth' => function ($username, $password) {
                /**
                 * TODO WWW-Authenticate validate if need
                if (!\Yii::$app->getRequest()->headers->has('WWW-Authenticate')) {
                    return null;
                };
                if (\Yii::$app->getRequest()->headers->get('WWW-Authenticate') !== 'Basic realm="webapi"') {
                    return null;
                }*/

                if ($password === null) {
                    $user = User::find()->where(['auth_key' => $username])->one(); // 1capi brBhc-CmLmDYwDNz8nrQ3bMCVXdeFYN6
                    return $user;
                }
                $user = User::findByUsername($username, 'username');
                if ($user !== null) {
                    if ($user->validatePassword($password)) {
                        // TODO if ($user->hasRole('webapi'))
                        return $user;
                    }
                }
                return null;
            },
        ];
        return $behaviors;
    }

    public function actions()
    {
        return [
            'start-sync' => [
                'class' => StartSyncAction::className(),
            ],
            'save-data' => [
                'class' => SaveDataAction::className(),
            ],
            'get-data' => [
                'class' => GetDataAction::className(),
            ],
            'show-wsdl' => [
                'class' => ShowWsdlAction::className(),
            ]
        ];
    }

    /**
     * Default action for this controller
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;

        SOAP::getInstance()->log("Activated route 'frontend/index'", "info");
        if (isset($this->module->params['userAgent'])) {
            if ( !empty($this->module->params) && $request->getUserAgent() !== $this->module->params['userAgent']) {
                \Yii::$app->getErrorHandler()->exception = new ForbiddenHttpException(
                    "Access is not allowed for this user " . $request->getUserAgent(), 403);
                throw \Yii::$app->getErrorHandler()->exception;
            }
        }
        if (!$request->isPost) {
            $getParams = $request->getQueryParams();
            if (isset($getParams['wsdl']) && empty($getParams['wsdl'])) {
                if (count($getParams) == 1 || (count($getParams) == 2 && isset($getParams['token']))) {
                    $response = $this->runAction('show-wsdl');
                } else {
                    \Yii::$app->getErrorHandler()->exception = new ForbiddenHttpException(
                        "Bad request for get WSDL", 400);
                    throw \Yii::$app->getErrorHandler()->exception;
                }
            } else {
                \Yii::$app->getErrorHandler()->exception = new MethodNotAllowedHttpException(
                    "Method " . $request->getMethod() . " not allowed", 405);
                throw \Yii::$app->getErrorHandler()->exception;
            }
        } else {
            $processedRequest = SoapRequestFormatter::processRequest(file_get_contents('php://input'));
            $actions = $this->actions();
            unset($actions['show-wsdl']);
            if (isset($actions[$processedRequest['action_id']])) {
                if ($request->getHeaders()->get('soapaction') !== "\"#{$processedRequest['action']}\"") {
                    throw new ErrorException(
                        "The SOAP action specified on the header, does not match requested SOAP Action");
                }
                $response = $this->runAction($processedRequest['action_id'], ['request' => $processedRequest['request']]);
            } else {
                throw new ErrorException("The requested SOAPAction is not registered", 400);
            }
        }
        SOAP::getInstance()->log("Completed route 'frontend/index'", "info");
        return $response;
    }
}
