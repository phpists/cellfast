<?php
namespace noIT\user\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

class ResetPasswordAction extends Action {
	public $formModel = 'noIT\user\models\ResetPasswordForm';
	public $formModelOptions = [];
	public $view = '@noIT\user\views\resetPassword';

	public function run($token) {
		$this->formModelOptions['token'] = $token;
		try {
			$model = Yii::createObject($this->formModel, $this->formModelOptions);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'New password saved.'));

			return $this->controller->goHome();
		}

		return $this->controller->render($this->view, [
			'model' => $model,
		]);
	}
}