<?php

namespace noIT\tips\controllers;

use noIT\tips\Module;
use Yii;
use noIT\tips\models\Tip;
use noIT\tips\models\TipSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TipController implements the CRUD actions for Tip model.
 */
class TipController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'body-image-upload' => [
				'class' => 'vova07\imperavi\actions\UploadFileAction',
				'url' => Yii::getAlias('@cdnUrl/tips/body'),
				'path' => Yii::getAlias('@cdn/tips/body'),
				'unique' => true,
				'translit' => true,
			],
			'body-images-get' => [
				'class' => 'vova07\imperavi\actions\GetImagesAction',
				'url' => Yii::getAlias('@cdnUrl/tips/body'),
				'path' => Yii::getAlias('@cdn/tips/body'),
				'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif']],
			],
			'body-file-delete' => [
				'class' => 'vova07\imperavi\actions\DeleteFileAction',
				'url' => Yii::getAlias('@cdnUrl/tips/body'),
				'path' => Yii::getAlias('@cdn/tips/body'),
			]
		];
	}

	/**
	 * {@inheritdoc}
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

	/**
	 * Lists all Tip models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new TipSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new Tip model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Tip();

		$tipModule = Module::getInstance();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}

		return $this->render('create', [
			'model' => $model,
			'tipModelsName' => $tipModule->models,
			'tipModelAttributes' => [],
		]);
	}

	/**
	 * Updates an existing Tip model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		$tipModule = Module::getInstance();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['update', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
			'tipModelsName' => $tipModule->models,
			'tipModelAttributes' => $tipModule->getCleanModelAttributes($model->model),
		]);
	}

	/**
	 * Deletes an existing Tip model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Tip model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Tip the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Tip::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}

	public function actionGetSelectedModelAttribute()
	{
		$this->enableCsrfValidation = false;

		Yii::$app->response->format = Response::FORMAT_JSON;

		$modelName = Yii::$app->request->post('depdrop_all_params');

		if (!empty($modelName) && isset($modelName['model-id']) ) {

            $modelName = $modelName['model-id'];

            $tipModule = Module::getInstance();

            $output = [];

            foreach ($tipModule->getCleanModelAttributes($modelName) as $attribute => $label) {

                $output[] = [
                    'id' => $attribute,
                    'name' => $label,
                ];

            }

		}

		return ['output' => $output, 'selected' => ''];
	}

}
