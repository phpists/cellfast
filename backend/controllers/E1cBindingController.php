<?php

namespace backend\controllers;

use backend\models\E1cGroupOfGoodSearch;
use backend\models\E1cGoodSearch;
use backend\models\PriceType;
use backend\models\ProductPrice;
use common\helpers\SiteHelper;
use common\models\Product;
use common\models\ProductItem;
use common\models\soap\E1cClient;
use common\models\soap\E1cGood;
use common\models\soap\E1cGroupOfGood;
use common\models\soap\E1cHeadOfOrder;
use common\models\soap\E1cTypeOfPrice;
use common\models\soap\E1cWarehouse;
use common\models\ProductType;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Интерфейсы связывания элементов
 */
class E1cBindingController extends Controller {

	/**
	 * Сводные данные
	 */
	public function actionIndex() {
		$scopes = [
			E1cGood::className() => \common\models\ProductItem::className(),
			E1cGroupOfGood::className() => ProductType::className(),
			E1cTypeOfPrice::className() => \common\models\PriceType::className(),
			E1cWarehouse::className() => \common\models\Warehouse::className(),
			E1cClient::className() => \common\models\User::className(),
			E1cHeadOfOrder::className() => \common\models\Order::className(),
		];

		$allModels = [];
		foreach ($scopes as $e1cEntity => $appEntity) {
			$total = call_user_func([$e1cEntity, 'find'])->count('id');

			$binded = (new Query())
				->select('e1c.id')
				->from(call_user_func([$e1cEntity, 'tableName']) .' e1c')
				->leftJoin(call_user_func([$appEntity, 'tableName']) .' app', 'e1c.id = app.e1c_id')
				->where(['IS NOT', 'app.id', null])
				->andWhere(['e1c.exclude_binding' => false])
				->count('e1c.id');

			$loners = (new Query())
				->select('e1c.id')
				->from(call_user_func([$e1cEntity, 'tableName']) .' e1c')
				->leftJoin(call_user_func([$appEntity, 'tableName']) .' app', 'e1c.id = app.e1c_id')
				->where(['IS', 'app.id', null])
				->andWhere(['e1c.exclude_binding' => false])
				->count('e1c.id');

			$excluder = call_user_func([$e1cEntity, 'find'])->where(['exclude_binding' => true])->count('id');

			$allModels[$e1cEntity] = [
				'total' => $total,
				'binded' => $binded,
				'loners' => $loners,
				'excluder' => $excluder,
			];
		}

		$allModels[E1cGood::className()]['name'] = Yii::t('app', 'Products');
		$allModels[E1cGroupOfGood::className()]['name'] = Yii::t('app', 'Product types');
		$allModels[E1cTypeOfPrice::className()]['name'] = Yii::t('app', 'Price types');
		$allModels[E1cWarehouse::className()]['name'] = Yii::t('app', 'Warehouses');
		$allModels[E1cClient::className()]['name'] = Yii::t('app', 'Users');
		$allModels[E1cHeadOfOrder::className()]['name'] = Yii::t('app', 'Orders');

		$dataProvider = new ArrayDataProvider([
			'allModels' => $allModels,
		]);

		return $this->render('index', [
			'active' => 'summary',
			'searchModel' => null,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionGood() {
		$searchModel = new E1cGoodSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'active' => 'good',
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionGroup() {
		$searchModel = new E1cGroupOfGoodSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'active' => 'group',
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
     * Связь E1cGroupOfGood с ProductTypes
     */
	public function actionBindingGroup() {
		if ( ! Yii::$app->request->isAjax ) {
			throw new NotFoundHttpException();
		}
		Yii::$app->response->format = Response::FORMAT_JSON;
		if ( !($groupId = Yii::$app->request->post('editableKey')) || ! ($e1cGroup = E1cGroupOfGood::findOne($groupId)) ) {
			throw new NotFoundHttpException();
		}
		$i = Yii::$app->request->post( 'editableIndex' );
		$linkItems = $_POST['E1cGroupOfGood'][ $i ]['appEntitiesLabel'];

		Yii::$app->db->createCommand("UPDATE ". ProductType::tableName() ." SET e1c_id=null WHERE e1c_id={$groupId}")
		             ->execute();

		foreach ($linkItems as $linkId) {
			if ( !($model = ProductType::findOne($linkId)) ) {
				continue;
			}
			$model->e1c_id = $groupId;
			$model->save(false, ['e1c_id']);
		}

		// Reload AR
		$e1cGroup = E1cGroupOfGood::findOne($groupId);
		return [
			'output' => $e1cGroup->appEntitiesLabel,
			'message' => ''
		];
	}

	/**
	 * Связь E1cGood с ProductItem (точнее - создание ProductItem и его связывание с E1cGood)
	 */
	public function actionBindingGood() {
        $messages = [
            'error' => [],
            'success' => [],
        ];

	    // Binding process
	    if (Yii::$app->request->isPost) {
            // List of goods with defined features

            /** @var $product Product */
            if ( ! ($product = \backend\models\Product::findOne(Yii::$app->request->get('id'))) ||
                ! ($item_ids = Yii::$app->request->post('item_id')) ||
                ! ($item_names = Yii::$app->request->post('item_name')) ||
                ! ($item_features = Yii::$app->request->post('product_feature'))) {
                throw new NotFoundHttpException();
            }

            $ids = array_keys($item_names);

            // Get exists price type ids
            $price_types_ids = ArrayHelper::map(PriceType::find()->select(['id', 'e1c_id'])->column(), 'id', 'e1c_id');

            foreach (E1cGood::find()->where(['id' => $ids])->all() as $e1cGood) {
                $e1cGoodId = $e1cGood->id;
                if (!isset($item_names[$e1cGoodId]) || !isset($item_features[$e1cGoodId])) {
                    Yii::$app->session->setFlash('success', "Номенклатура {$e1cGood->name} пропущена");
                    continue;
                }

                $features = [];
                foreach ($item_features[$e1cGoodId] as $feature_id => $feature_value) {
                    $features[$feature_id] = [$feature_value];
                }

                $features_check = [];
                foreach ($features as $_features) {
                    $features_check[] = implode('', $_features);
                }
                if (!implode('', $features_check)) {
                    continue;
                }

                $model = !empty($item_ids[$e1cGoodId]) ? ProductItem::findOne($item_ids[$e1cGoodId]) : new ProductItem();

                $model->native_name = $item_names[$e1cGoodId];
                $model->product_id = $product->id;
                $model->project_id = $product->project_id;
                $model->e1c_id = $e1cGoodId;
                $model->sku = $e1cGood->code_vendor;

                // Set prices
                $model->prices = [];
                foreach ($e1cGood->e1cPrices as $e1cPrice) {
                    if ( $e1cPrice->type_of_price_id === null || !($price_type_id = array_search($e1cPrice->type_of_price_id, $price_types_ids))) {
                        continue;
                    }
                    $model->prices[$price_type_id] = $e1cPrice->value;
                }


                $model->featureGroupedValueIds = $features;

                if ($model->save()) {
                    $messages['success'][] = "{$model->native_name} сохранен";
                } else {
                    $messages['error'][] = $model->native_name .' => '. SiteHelper::getErrorMessages($model->errors, false);
                    continue;
                }
            }

            return $this->redirect(['binding-good', 'id' => $product->id, 'ids' => implode(',', $ids)]); // binding-good', 'id' => $product->id, 'ids' => $ids
        }

        if (!isset($product)) {
            $product = \backend\models\Product::findOne(Yii::$app->request->get('id'));
        }

        if (!isset($ids)) {
            $ids = Yii::$app->request->get('ids');
        }

		if ( !$product || !$ids ) {
			throw new NotFoundHttpException();
		}

		if (!is_array($ids)) {
		    $ids = explode(',', $ids);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => E1cGood::find()->where(['id' => $ids]),
        ]);

		foreach ($messages as $type => $message) {
            Yii::$app->session->setFlash($type, $message);
        }

		return $this->render('/e1c-binding/good/binding', [
		    'dataProvider' => $dataProvider,
            'product' => $product,
            'definedFeatures' => $product->type->product_features_define,
        ]);
	}
}