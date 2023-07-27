<?php

namespace backend\controllers;

use Yii;
use backend\models\Category;
use backend\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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

    public function actions() {
        return [
            'body-image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => Yii::getAlias('@cdnUrl/category/uploads'),
                'path' => Yii::getAlias('@cdn/category/uploads'),
                'unique' => false,
                'translit' => true,
            ],
            'body-images-get' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' => Yii::getAlias('@cdnUrl/category/uploads'),
                'path' => Yii::getAlias('@cdn/category/uploads'),
                'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif']],
            ],
            'body-file-delete' => [
                'class' => 'vova07\imperavi\actions\DeleteFileAction',
                'url' => Yii::getAlias('@cdnUrl/category/uploads'),
                'path' => Yii::getAlias('@cdn/category/uploads'),
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();

        $parent_id = Yii::$app->request->post('expandRowKey', null);

        $params = Yii::$app->request->queryParams;

        if (!$parent_id) {
            $parent_id = 0;
        }
        $params['CategorySearch']['parent_id'] = (int)$parent_id;

        $dataProvider = $searchModel->search($params);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'searchMode' => Yii::$app->request->get('CategorySearch'),
                'mode' => 'children',
                'gridId' => 'subcategories-grid-'. $parent_id,
            ]);
        }

        if (Yii::$app->request->get('CategorySearch')) {
            $mode = 'search';
            $gridId = 'categories-search-grid';
        } else {
            $mode = 'root';
            $gridId = 'categories-grid';
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mode' => $mode,
            'gridId' => $gridId,
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
        $model = new Category();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['create']);
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

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
