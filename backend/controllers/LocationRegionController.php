<?php

namespace backend\controllers;

use common\helpers\SiteHelper;
use Yii;
use common\models\LocationRegion;
use common\models\LocationRegionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LocationRegionController implements the CRUD actions for LocationRegion model.
 */
class LocationRegionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

	public function actions()
	{
		return [
			'body-image-upload' => [
				'class' => 'vova07\imperavi\actions\UploadFileAction',
				'url' => Yii::getAlias('@cdnUrl/location-region/body'),
				'path' => Yii::getAlias('@cdn/location-region/body'),
				'unique' => true,
				'translit' => true,
			],
			'body-images-get' => [
				'class' => 'vova07\imperavi\actions\GetImagesAction',
				'url' => Yii::getAlias('@cdnUrl/location-region/body'),
				'path' => Yii::getAlias('@cdn/location-region/body'),
				'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif']],
			],
			'body-file-delete' => [
				'class' => 'vova07\imperavi\actions\DeleteFileAction',
				'url' => Yii::getAlias('@cdnUrl/location-region/body'),
				'path' => Yii::getAlias('@cdn/location-region/body'),
			]
		];
	}

    /**
     * Lists all LocationRegion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationRegionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LocationRegion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new LocationRegion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LocationRegion();

        if ($model->load(Yii::$app->request->post())) {
        	if ($model->save()) {
		        return $this->redirect(['index', 'id' => $model->id]);
	        }
	        Yii::$app->session->setFlash('error', SiteHelper::getErrorMessages($model->errors, false));
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LocationRegion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

	    if ($model->load(Yii::$app->request->post())) {
		    if ($model->save()) {
			    return $this->redirect(['index', 'id' => $model->id]);
		    }
		    Yii::$app->session->setFlash('error', SiteHelper::getErrorMessages($model->errors, false));
	    }
	    return $this->render('create', [
		    'model' => $model,
	    ]);
    }

    /**
     * Deletes an existing LocationRegion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LocationRegion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LocationRegion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LocationRegion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
