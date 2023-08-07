<?php
namespace bryza\controllers;

use backend\models\Product;
use common\models\Document;
use common\models\Event;
use common\models\LocationRegion;
use common\models\News;
use common\models\Partner;
use noIT\content\models\ContentSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use bryza\models\LoginForm;
use bryza\models\SignupForm;
use yii\base\InvalidParamException;
use bryza\models\ContactForm;
use yii\web\BadRequestHttpException;
use bryza\models\PasswordResetRequestForm;
use bryza\models\ResetPasswordForm;
use Zxing\NotFoundException;

/**
 * Site controller
 */
class SiteController extends \common\controllers\SiteController
{
	/**
	 * @return string
	 */
	public function actionPartners()
	{
		$onlinePartnersModel = Partner::find()
		                              ->where([
			                              'status' => Partner::ENABLE,
			                              'type' => Partner::TYPE_ONLINE,
		                              ])
		                              ->andWhere([
			                              'like', 'projects', Yii::$app->projects->current->alias,
		                              ])
		                              ->all();

		$models = LocationRegion::find()->with([
			'partner' => function (\yii\db\ActiveQuery $query) {
		         $query->where([
			         'like', 'projects', Yii::$app->projects->current->alias,
		         ]);
		         $query->andWhere([
			         'type' => Partner::TYPE_OFFLINE,
			         'status' => Partner::ENABLE,
		         ]);
		     },
		])->where([
			'status' => LocationRegion::STATUS_ACTIVE,
		])->all();

		$locationRegionModel = [];

		foreach($models as $model) {

			if(!empty($model->partner)) {
				$locationRegionModel[] = $model;
			}
		}

		return $this->render('partners', [
			'onlinePartnersModel' => $onlinePartnersModel,
			'locationRegionModel' => $locationRegionModel,
		]);
	}

	public function actionDownload($type = Document::TYPE_DOCUMENT)
	{
		$requestURL = basename(Yii::$app->request->url);

		switch ($requestURL) {
			case Document::TYPE_DOCUMENT:
				$type = Document::TYPE_DOCUMENT;
				break;
			case Document::TYPE_CERTIFICATE:
				$type = Document::TYPE_CERTIFICATE;
				break;
			case Document::TYPE_PRICE_LIST:
				$type = Document::TYPE_PRICE_LIST;
				break;
			case Document::TYPE_CATALOG:
				$type = Document::TYPE_CATALOG;
				break;
		}

		$dataProvider = new ActiveDataProvider();

		$dataProvider->pagination->pageSize = Document::PAGE_SIZE;

		$dataProvider->query = Document::find()
		                               ->where([
			                               'status' => Document::ENABLE,
			                               'type' => $type,
			                               'project_id' => Yii::$app->projects->current->alias,
		                               ]);

		return $this->render("download/{$type}", ['dataProvider' => $dataProvider]);
	}
    public function actionSearch()
    {
        $searchRequest = Yii::$app->request->get('search_header');
        $search = str_replace(' ', '', $searchRequest);
        $query = Product::find()->where(['LIKE', 'replace(native_name, " ", "")', $search]);
        $this->setMeta('Пошук', 'product');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 3],
        ]);

        $jsonData = Json::encode($dataProvider);
        Yii::info('jsonData: ' . print_r($jsonData, true));

        return $this->render('_header', ['jsonData' => $jsonData]);
    }
}