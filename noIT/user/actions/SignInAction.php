<?php
namespace noIT\user\actions;


use Yii;
use yii\base\Action;

class SignInAction extends Action {
	public $formModel = 'noIT\user\models\LoginForm';
	public $formModelOptions = [];
	public $view = '@noIT\user\views\login';

	public function run() {
		if (!Yii::$app->user->isGuest) {
			return $this->controller->goHome();
		}

		$model = Yii::createObject($this->formModel, $this->formModelOptions);
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->controller->goBack();
		} else {
			$model->password = '';

			return $this->controller->render($this->view, [
				'model' => $model,
			]);
		}
	}
}