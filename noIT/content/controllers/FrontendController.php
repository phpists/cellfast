<?php

namespace noIT\content\controllers;

use Yii;
use yii\web\Controller;
use noIT\content\models\Content;
use yii\web\NotFoundHttpException;
use noIT\content\models\ContentSearch;

class FrontendController extends Controller
{
    protected $modelClass = 'noIT\content\models\Content';
    protected $modelSearchClass = 'noIT\content\models\ContentSearch';

    /**
     * Lists all ContentEvent models.
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var ContentSearch $searchModel */
        $searchModel = new $this->modelSearchClass();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
	        'params' => Yii::$app->request->queryParams,
        ]);
    }

    /**
     * Displays a single ContentEvent model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($url)
    {
        return $this->render('view', [
            'model' => $this->findModelByUrl($url),
        ]);
    }

    /**
     * Finds the ContentEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = call_user_func([$this->modelClass, 'findOne'], $id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the ContentEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelByUrl($url)
    {
        if ( ($model = call_user_func([$this->modelClass, 'find'])->where(['slug' => $url])->one() ) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}