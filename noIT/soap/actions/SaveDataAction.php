<?php

namespace noIT\soap\actions;

use noIT\soap\models\SoapRequestModel;
use noIT\soap\SoapServerModule as SOAP;
use yii\base\Action;
use yii\base\ErrorException;

class SaveDataAction  extends Action
{
    public function run(array $request)
    {
        $module = SOAP::getInstance();
        $module->log("Activated route 'soap-actions/save-data'", "info");
        $soapRequest = new SoapRequestModel(['scenario' => SoapRequestModel::SCENARIO_SAVE_DATA]);
        if (!$soapRequest->load($request)->validate()) {
            throw new ErrorException(
                "An error occurred during the validation SOAP Request: " .
                implode("; ", $soapRequest->getFirstErrors()), 400);
        }
        $soapRequest->entityName::loadAndSave($soapRequest->needToTruncateTable, $soapRequest->rows);
        \Yii::$app->getResponse()->data = [
            'saveDataResponse' => [
                'response' => [
                    'code' => 200,
                    'date' => date("Y-m-d\TH:i:s"),
                    'description' => "OK"
                ]
            ]
        ];
        $module->log("Completed route 'soap-actions/save-data'", "info");
        return \Yii::$app->getResponse();
    }
}