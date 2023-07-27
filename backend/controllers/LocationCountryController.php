<?php

namespace backend\controllers;

use noIT\core\helpers\SiteHelper;
use Yii;
use common\models\LocationCountry;
use common\models\LocationCountrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LocationCountryController implements the CRUD actions for LocationCountry model.
 */
class LocationCountryController extends Controller
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
				'url' => Yii::getAlias('@cdnUrl/location-country/body'),
				'path' => Yii::getAlias('@cdn/location-country/body'),
				'unique' => true,
				'translit' => true,
			],
			'body-images-get' => [
				'class' => 'vova07\imperavi\actions\GetImagesAction',
				'url' => Yii::getAlias('@cdnUrl/location-country/body'),
				'path' => Yii::getAlias('@cdn/location-country/body'),
				'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif']],
			],
			'body-file-delete' => [
				'class' => 'vova07\imperavi\actions\DeleteFileAction',
				'url' => Yii::getAlias('@cdnUrl/location-country/body'),
				'path' => Yii::getAlias('@cdn/location-country/body'),
			]
		];
	}

    /**
     * Lists all LocationCountry models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationCountrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LocationCountry model.
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
     * Creates a new LocationCountry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LocationCountry();

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
     * Updates an existing LocationCountry model.
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
     * Deletes an existing LocationCountry model.
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
     * Finds the LocationCountry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LocationCountry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LocationCountry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
