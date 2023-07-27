<?php
namespace backend\controllers;

use common\models\settings\AboutUsSettings;
use common\models\settings\ContactsSettings;
use common\models\settings\EmailSettings;
use common\models\settings\TipModalSettings;
use Yii;
use yii\web\Controller;

class SettingsController extends Controller
{
	public function actionEmail()
	{
		$model = new EmailSettings();

		if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			$model->save();
		}

		return $this->render('email', ['model' => $model]);
	}

	public function actionContactsPage()
	{
		$model = new ContactsSettings();

		if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			$model->save();
		}

		return $this->render('contacts-page', ['model' => $model]);
	}

	public function actionTips()
	{
		$model = new TipModalSettings();

		if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
			$model->save();
			return $this->refresh();
		}

		return $this->render('tips', ['model' => $model]);
	}

}