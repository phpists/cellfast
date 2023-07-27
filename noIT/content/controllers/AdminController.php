<?php

namespace noIT\content\controllers;

use Yii;
use noIT\content\models\Content;
use noIT\content\models\ContentSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class AdminController extends Controller {
    protected $modelClass = 'noIT\content\models\Content';
    protected $modelSearchClass = 'noIT\content\models\ContentSearch';

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
			'image-upload' => [
				'class' => 'vova07\imperavi\actions\UploadAction',
				'url' => Url::to('', true), // Directory URL address, where files are stored.
				'path' => '@alias/to/my/path' // Or absolute path to directory where files are stored.
			],
		];
	}

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
        ]);
    }

    /**
     * Displays a single ContentEvent model.
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
     * Creates a new ContentEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var Content $model */
        $model = new $this->modelClass();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ContentEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ContentEvent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

	public function actionImagesDelete($id) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		if ( !($key = Yii::$app->request->post('key'))
		     || !( $entity = $this->findModel($id) )
		     || !( $model = call_user_func([$entity->imagesUploadClass, 'findByEntity'], $entity->imagesUploadEntity, $entity->id) ) ) {
			return [
				'message' => 'Некорректный запрос',
			];
		}

		if (!$model->delete()) {
			return [
				'message' => 'Файл не удален',
			];
		}

		return [
		];
	}

	public function actionImagesUpload($id) {
		$model = $this->findModel($id);

		$model->imagesUpload = UploadedFile::getInstances($model, 'imagesUpload');

		$model->imagesUpload();

		Yii::$app->response->format = Response::FORMAT_JSON;

		return [];
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
}