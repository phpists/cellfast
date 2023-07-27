<?php

namespace noIT\soap\actions;

use common\models\soap\E1cSession;
use noIT\soap\models\SoapRequestModel;
use noIT\soap\SoapServerModule as SOAP;
use yii\base\Action;

class StartSyncAction extends Action
{
    public function run(array $request)
    {
        $module = SOAP::getInstance();
        $module->log("Activated route 'soap-actions/start-sync'", "info");
        $soapRequest = new SoapRequestModel(['scenario' => SoapRequestModel::SCENARIO_START_SYNC]);
        if (!$soapRequest->load($request)->validate()) {
            throw new \ErrorException(
                "An error occurred during the validation SOAP Request: " .
                implode("; ", $soapRequest->getFirstErrors()), 400);
        }
        $guidSession = E1cSession::loadAndSave($soapRequest->timePoint, $soapRequest->rows);
        \Yii::$app->getResponse()->data = [
            'startSyncResponse' => [
                'response' => [
                    'code' => 200,
                    'date' => date("Y-m-d\TH:i:s"),
                    'description' => "OK",
                    'guid' => $guidSession
                ]
            ]
        ];
        $module->log("Completed route 'soap-actions/start-sync'", "info");
        return \Yii::$app->getResponse();
    }
}