<?php
namespace cellfast\controllers;

use common\models\Category;
use common\models\Product;
use common\models\ProductEntity;
use common\models\ProductFeature;
use common\models\ProductFeatureEntity;
use common\models\ProductItem;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class CatalogController extends \common\controllers\CatalogController
{

}