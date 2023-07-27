<?php
namespace common\controllers;

use common\helpers\SiteHelper;
use common\models\AboutUs;
use common\models\WriteToUsFeedback;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	/**
	 * Displays tmp_about.
	 *
	 * @return mixed
	 */
	public function actionAbout()
	{
		$model = AboutUs::find()->where([
			'project_id' => SiteHelper::getProject()
		])->one();

		return $this->render('about', ['model' => $model]);
	}

	/**
	 * Displays contact page.
	 *
	 * @return mixed
	 */
	public function actionContact()
	{
		$model = new WriteToUsFeedback();

		$suffix = Yii::$app->languages->current->suffix;

		$attribute = 'contact__' . Yii::$app->projects->current->alias;

		if( !($contactsData = unserialize(Yii::$app->settings->get($attribute, 'ContactsSettings'))) ) {
			$contactsData = [];
		}

		$data = [];

		foreach($contactsData as $singleContactsData) {
			$data[] = [
				'label' => $singleContactsData["label_{$suffix}"],
				'coordinate' => $singleContactsData["coordinate"],
				'location' => $singleContactsData["location_{$suffix}"],
				'phone' => $singleContactsData["phone"],
				'email' => $singleContactsData["email"],
				'work_time' => $singleContactsData["work_time"],
			];
		}

		return $this->render('contact/index', [
			'model' => $model,
			'contactsData' => $data
		]);
	}
}
