<?php
namespace backend\controllers;

use common\models\Banner;
use Yii;
use yii\web\Controller;
use zxbodya\yii2\galleryManager\GalleryManagerAction;

class BannerController extends Controller
{
	public function actions()
	{
		return [
			'galleryApi' => [
				'class' => GalleryManagerAction::className(),
				'types' => [
					'banner-cellfast' => Banner::className(),
					'banner-bryza' => Banner::className(),
					'banner-ines' => Banner::className(),
				]
			],
		];
	}

	public function actionIndex()
	{
		$model = new Banner();

		if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			$model->save();
			$this->refresh();
		}

		return $this->render('index', ['model' => $model]);
	}

}