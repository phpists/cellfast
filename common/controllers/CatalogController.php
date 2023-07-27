<?php
namespace common\controllers;

use common\models\Category;
use common\models\Product;
use common\models\ProductEntity;
use common\models\ProductFeature;
use common\models\ProductFeatureEntity;
use common\models\ProductFeatureValue;
use common\models\ProductImage;
use common\models\ProductItem;
use common\models\ProductTypeHasProductFeature;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class CatalogController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['index', 'category'],
				'rules' => [
					[
						'actions' => ['index', 'category'],
						'allow' => true,
					],
				],
			],
		];
	}

	/**
	 * Displays catalog homepage (categories list).
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->render('index', [
			'tree' => Yii::$app->category->getCategoriesTree()
		]);
	}

	/**
	 * Displays category.
	 *
	 * @return mixed
	 */
	public function actionCategory()
	{
		/** @var Category $category */
		if ( !($category = Yii::$app->request->get('category')) ) {
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
		}

		if( !($subcategories = $category->subcategories) ) {
			$subcategories = [];
		}

		if ( ! $category->product_type_id ) {
			// Если категория не привязана к типу товаров,
			// то выводим товары первой подкатегории ~подкатегории~

			return $this->render('index', [
				'tree' => Yii::$app->category->getCategoriesTree($category->id),
				'category' => $category,
			]);
		}

        $params = Yii::$app->request->queryParams;

        $filter = Yii::$app->request->get('filter', []);

        $params['features'] = empty($filter['features']) ? [] : $filter['features'];
        $params['prices'] = empty($filter['prices']) ? [] : $filter['prices'];

        /** Получаем список опций фильтра */
        $featureQuery = $category->getFeaturesForFilter();

        $featureIds = $featureQuery->select(ProductFeature::tableName() .'.id')->column();
        $features = [];

        foreach ($featureIds as $feature_id) {

            $feature = ProductFeatureEntity::findOne($feature_id);
            $features[$feature_id] = [
                'group' => $feature,
                'values' => $feature->values,
            ];

            // todo Set logic hidden filter-items
//            foreach ($features[$feature_id]['values'] as $i => $value) {
//			    if ( !array_key_exists($value->id, $featureAllValues) ) {
//			    	unset($features[$feature_id]['values'][$i]);
//				    continue;
//			    }
//			    if ( !array_key_exists($value->id, $featureValues) ) {
//				    $features[$feature_id]['values'][$i]->passive = true;
//			    }
//            }
        }

        foreach ($features as $i => $feature) {
            if ( empty($feature['values']) || count($feature['values']) < 2 ) {
                unset($features[$i]);
            }
        }

        if ( ($manualId = Yii::$app->request->get('manual', false)) !== false && array_key_exists($manualId, $category->manuals) ) {
            return $this->render('product/manual', [
                'subcategories' => $subcategories,
                'features' => $features,
                'params' => $params,
                'featureIds' => $featureIds,
                'category' => $category,
                'manualId' => $manualId,
                'manual' => $category->manuals[$manualId],
            ]);
        }

        // Get all products (without filters)
		$allDataProvider = $category->getProducts(['category' => $params['category']]);

		if ($allDataProvider) {
			// todo Set logic hidden filter-items
			$featureAllValues = [];
			foreach (ProductFeatureValue::getByProducts(ArrayHelper::getColumn($allDataProvider->allModels, 'id')) as $_id) {
				$featureAllValues[$_id] = $_id;
			}
		}

		// Get products with filters if isset
		if ($allDataProvider && $filter) {
			$dataProvider = $category->getProducts($filter);

			// todo Set logic hidden filter-items
//		    foreach (ProductFeatureValue::getByProducts($dataProvider->query->select('p.id')->column()) as $_id) {
//			    $featureValues[ $_id ] = $_id;
//		    }
		} else {
			$dataProvider = $allDataProvider;

			// todo Set logic hidden filter-items
//		    $featureValues = $featureAllValues;
		}

		if($dataProvider) {
			$dataProvider->pagination->pageSize = Category::PAGE_SIZE;
		}

        // Массив IDs всех фильтров
        $featureIds = [];
        foreach ($params['features'] as $_features) {
            foreach ($_features as $_feature) {
                $featureIds[] = $_feature->id;
            }
        }

		return $this->render('product/index', [
			'dataProvider' => $dataProvider,
			'subcategories' => $subcategories,
			'features' => $features,
            'params' => $params,
            'featureIds' => $featureIds,
		]);
	}

	public function actionProduct()
	{
		/** @var Product $product */
		if ( !($product = Yii::$app->request->get('product')) ) {
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
		}

		$imagesArray = [];

		foreach ($product->images as $image) {
			$imagesArray[] = [
				'thumb' => Yii::getAlias('@cdnUrl') . '/' . $image['entity'] . '/'. $image['entity_id'] . '/styles/admin_thumb_lg/'. $image['src'],
				'original' => Yii::getAlias('@cdnUrl') . '/' . $image['entity'] . '/'. $image['entity_id'] . '/'. $image['src'],
			];
		}

		return $this->render('product/view', [
			'model' => $product,
			'imagesArray' => $imagesArray,
            'item' => Yii::$app->request->get('item'),
            'featureIds' => json_decode(Yii::$app->request->get('filter')),
		]);
	}

	public function actionProductItems()
	{
		/** @var Product $product */
		if ( !($product = Yii::$app->request->get('product')) ) {
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
		}

		/*if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			$items = [];
			foreach ($product->items as $item) {
				$items[] = $item->toArray([
					'id',
					'name',
					'sku',
					'price',
					'commonPrice',
				]);
			}

			return [
				'product' => $product->toArray(['id', 'name']),
				'items' => $items,
			];
		}*/

		$dataProvider = new ActiveDataProvider([
			'query' => $product->getItems(),
		]);

		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('product/item/index', [
				'product' => $product,
				'dataProvider' => $dataProvider,
			]);
		}

		return $this->render('product/item/index', [
			'product' => $product,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionProductItem()
	{
		if ( ! Yii::$app->request->isAjax ) {
			throw new NotFoundHttpException();
		}
		/** @var ProductEntity $product */
		if ( !($product = Yii::$app->request->get('product')) || !($features = Yii::$app->request->post('features', '')) ) {
			throw new NotFoundHttpException();
		}
		$features = explode(',', $features);

		Yii::$app->response->format = Response::FORMAT_JSON;
		/** @var $item ProductItem */
		if ( !($item = $product->getItemByFeatures($features)) ) {
			return [
				'error' => Yii::t('app', 'Product not found'),
			];
		}

		return $item->toArray();
	}
}
