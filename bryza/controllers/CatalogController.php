<?php
namespace bryza\controllers;

use common\components\GdsCalc\widgets\CalcDrainWidget;
use Yii;

/**
 * Site controller
 */
class CatalogController extends \common\controllers\CatalogController
{
    public function actionGdsCalc()
    {
        $this->view->title = Yii::t('app', 'Online calculation of the drainage system');
        return $this->render('gds-calc', []);
    }
}
