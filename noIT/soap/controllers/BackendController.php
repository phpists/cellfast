<?php

namespace noIT\soap\controllers;

use noIT\soap\models\SoapE1cSessionLog;
use yii\web\Controller;

/**
 * Backend controller for the `soap_server` module
 */
class BackendController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $params['dataProvider'] = SoapE1CSessionLog::list(\Yii::$app->request->queryParams);
        return $this->render('index', $params);
    }

    public function actionView($guid)
    {
        $params['dataProvider'] = SoapE1CSessionLog::view($guid, \Yii::$app->request->queryParams);
        $params['guid'] = $guid;
        return $this->render('view', $params);
    }
}