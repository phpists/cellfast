<?php

namespace noIT\soap\actions;

use noIT\soap\SoapServerModule as SOAP;
use yii\base\Action;

class GetDataAction  extends Action
{
    public function run()
    {
        $module = SOAP::getInstance();
        $module->log("Activated route 'soap-actions/get-data'", "info");
        $module->log("Completed route 'soap-actions/get-data'", "info");
    }
}