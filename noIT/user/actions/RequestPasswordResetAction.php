<?php
namespace noIT\user\actions;


use Yii;
use yii\base\Action;

class RequestPasswordResetAction extends Action {
	public $formModel = 'noIT\user\models\ResetPasswordForm';
	public $formModelOptions = [];
	public $view = '@noIT\user\views\requestPasswordResetToken';

	public function run() {
		$model = Yii::createObject($this->formModel, $this->formModelOptions);

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if (method_exists($model, 'sendEmail')) {
				if ( $model->sendEmail() ) {
					Yii::$app->session->setFlash( 'success', Yii::t('app', 'Check your email for further instructions.') );
					return $this->controller->goHome();
				} else {
					Yii::$app->session->setFlash( 'error', Yii::t('app', Yii::t('app', 'Sorry, we are unable to reset password for the provided email address.')) );
				}
			}
		}

		return $this->controller->render($this->view, [
			'model' => $model,
		]);
	}
}