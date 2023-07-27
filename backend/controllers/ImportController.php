<?php

namespace backend\controllers;

use backend\models\ImportForm;
use backend\models\ProductItem;
use backend\models\ProductItemsCsvForm;
use backend\models\ProductTypeSearch;
use backend\models\Product;
use backend\models\ProductSearch;
use common\helpers\SiteHelper;
use noIT\content\controllers\AdminController;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ImportController - import data with files.
 */
class ImportController extends Controller
{
    public $defaultAction = 'upload';

    /**
     * Upload data from file
     * @return mixed
     */
    public function actionUpload()
    {
        $model = new ImportForm();

        if ($model->load(Yii::$app->request->post()) && $model->upload()) {
            $model->save();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
