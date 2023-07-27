<?php
namespace common\controllers;

use common\models\Feedback;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class FeedbackController extends Controller
{
	public function actionSendForm()
	{
		if(Yii::$app->request->isAjax) {

			Yii::$app->response->format = Response::FORMAT_JSON;

			$formData = Yii::$app->request->post();

			$modelName = base64_decode($formData['model']);

			if(isset($formData['secret_form_key']) && $formData['secret_form_key'] === Feedback::SECRET_KEY) {

				/** @var Feedback $model */
				$model = new $modelName();

				if ( $model->load($formData) ) {

					$model->save();

					if($model->sendEmailNotification) {
						$model->sendEmail();
					}
				}

			}

			return ['title'  => 'Спасибо!', 'status' => 'success'];
		}

	}
}