<?php

namespace backend\controllers;

use backend\models\ProductFeatureValue;
use common\helpers\AdminHelper;
use common\helpers\SiteHelper;
use Yii;
use backend\models\ProductFeature;
use backend\models\ProductFeatureSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ProductFeatureController implements the CRUD actions for ProductFeature model.
 */
class ProductFeatureController extends Controller
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
				'url' => Yii::getAlias('@cdnUrl/product-feature/body'),
				'path' => Yii::getAlias('@cdn/product-feature/body'),
				'unique' => true,
				'translit' => true,
			],
			'body-images-get' => [
				'class' => 'vova07\imperavi\actions\GetImagesAction',
				'url' => Yii::getAlias('@cdnUrl/product-feature/body'),
				'path' => Yii::getAlias('@cdn/product-feature/body'),
				'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif']],
			],
			'body-file-delete' => [
				'class' => 'vova07\imperavi\actions\DeleteFileAction',
				'url' => Yii::getAlias('@cdnUrl/product-feature/body'),
				'path' => Yii::getAlias('@cdn/product-feature/body'),
			]
		];
	}

	/**
	 * Lists all Category models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ProductFeatureSearch();

		$params = Yii::$app->request->queryParams;

		$dataProvider = $searchModel->search($params);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Category model.
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
	 * Creates a new Category model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new ProductFeature();

		if ($model->load(Yii::$app->request->post())) {
			if ($model->save()) {
				return $this->redirect(['update', 'id' => $model->id]);
			}
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing Category model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Category model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	public function actionAddValue()
	{
		if ( !\Yii::$app->request->isAjax || !\Yii::$app->request->isPost || !($feature_id = \Yii::$app->request->post('feature_id')) || !($value = \Yii::$app->request->post('value')) ) {
			throw new NotFoundHttpException();
		}
		/** @var ProductFeatureValue $model */
		$model = new ProductFeatureValue(['feature_id' => $feature_id]);
		$data = explode('|', $value);

		// Set value
		$model->value = $data[0];
		if (count($data) > 1) {
			// Try to set labels for langs
			foreach (array_values(\Yii::$app->languages->languages) as $i => $language) {
				if ( isset($data[$i+1]) && ($label = $data[$i+1]) ) {
					$model->setAttribute(AdminHelper::getLangField('value_label', $language), $label);
				}
			}
		}
		if (!$model->save()) {
			throw new Exception(implode("\n", SiteHelper::getErrorMessages($model->errors)));
		}
		\Yii::$app->response->format = Response::FORMAT_JSON;
		return [
			'id' => $model->id,
			'value_label' => $model->value_label,
		];
	}

	/**
	 * Finds the Category model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ProductFeature the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = ProductFeature::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
