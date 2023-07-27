<?php

namespace backend\controllers;

use backend\models\ProductItem;
use backend\models\ProductItemsCsvForm;
use backend\models\ProductTypeSearch;
use backend\models\Product;
use backend\models\ProductSearch;
use common\helpers\SiteHelper;
use noIT\content\controllers\AdminController;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends AdminController
{
	protected $modelClass = 'backend\models\Product';
	protected $modelSearchClass = 'backend\models\ProductSearch';

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
                'url' => Yii::getAlias('@cdnUrl/product/uploads'),
                'path' => Yii::getAlias('@cdn/product/uploads'),
                'unique' => false,
                'translit' => true,
            ],
            'body-images-get' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' => Yii::getAlias('@cdnUrl/product/uploads'),
                'path' => Yii::getAlias('@cdn/product/uploads'),
                'options' => ['only' => ['*.jpg', '*.jpeg', '*.png', '*.gif']],
            ],
            'body-file-delete' => [
                'class' => 'vova07\imperavi\actions\DeleteFileAction',
                'url' => Yii::getAlias('@cdnUrl/product/uploads'),
                'path' => Yii::getAlias('@cdn/product/uploads'),
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();

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
        $model = new Product();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Create successfull'));
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', SiteHelper::getErrorMessages($model->errors));
            }
        } else {
            Yii::$app->session->setFlash('error', SiteHelper::getErrorMessages($model->errors));
        }

        $model->type_id = Yii::$app->request->get('type', null);

        return $this->render('create', [
            'model' => $model,
            'types' => ProductTypeSearch::all(),
        ]);
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

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Update successfull'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', SiteHelper::getErrorMessages($model->errors));
            }
        }

        return $this->render('update', [
            'model' => $model,
            'types' => ProductTypeSearch::all(),
        ]);
    }

    public function actionItemAdd($id) {

        $model = $this->findModel($id);

        // Create and save product item
        $itemModel = new ProductItem();
        $itemModel->load(Yii::$app->request->post());
        $itemModel->product_id = $model->id;
        // Check to exist
//        if ($itemModel->checkExistsByFeatures()) {
//            /** TODO - Translate */
//            $message = Yii::t('app', 'Такая комбинация товара уже есть.');
//            if (Yii::$app->request->isAjax) {
//                Yii::$app->response = Response::FORMAT_JSON;
//                return [
//                    'error' => $message,
//                ];
//            }
//            Yii::$app->session->setFlash('error', $message);
//            $this->redirect(['update', 'id' => $model->id]);
//        }

        $itemModel->save();

        // Reload product model
        $model = $this->findModel($model->id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_items', ['model' => $model]);
        }

        return $this->redirect(['update', 'id' => $model->id]);
    }

    /**
     * Multiple create items
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionItemsAdd($id) {
        $model = $this->findModel($id);

        $csvModel = new ProductItemsCsvForm(['product_id' => $model->id]);

        $csvModel->load(Yii::$app->request->post());

        $csvModel->run();

        // Reload product model
        $model = $this->findModel($model->id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_items', ['model' => $model]);
        }

        return $this->redirect(['update', 'id' => $model->id]);
    }

    public function actionItemForm() {
        if ( !($id = Yii::$app->request->get('id')) ) {
            throw new NotFoundHttpException(Yii::t('app', 'The product item does not exist.'));
        }
        $model = $this->findItemModel($id);

        return $this->renderAjax('_item_update', ['model' => $model]);
    }

    public function actionItemUpdate() {
        if ( !($id = Yii::$app->request->get('id')) ) {
            throw new NotFoundHttpException(Yii::t('app', 'The product item does not exist.'));
        }
        $model = $this->findItemModel($id);

        if ( !$model->load(Yii::$app->request->post()) || !$model->save() ) {
        	var_dump($model::className(), $model->typeModelClass, $model->errors);
        	return;
        }

        // Reload product model
        $model = $this->findModel($model->product_id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_items', ['model' => $model]);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionItemDelete()
    {
        if ( !($id = Yii::$app->request->get('id')) ) {
            throw new NotFoundHttpException(Yii::t('app', 'The product item does not exist.'));
        }
        $itemModel = $this->findItemModel($id);
        $model = $this->findModel($itemModel->product_id);
        $itemModel->delete();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_items', ['model' => $model]);
        }

        return $this->redirect(['view', 'id' => $model->id]);
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
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Object model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findItemModel($id)
    {
        if (($model = ProductItem::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The product item does not exist.'));
        }
    }
}
