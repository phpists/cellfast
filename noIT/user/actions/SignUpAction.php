<?php
namespace noIT\user\actions;

use Yii;
use yii\base\Action;

class SignUpAction extends Action {
	public $formModel = 'noIT\user\models\SignupForm';
	public $formModelOptions = [];
	public $view = '@noIT\user\views\signup';

	public function run() {
		$model = Yii::createObject($this->formModel, $this->formModelOptions);
		if ($model->load(Yii::$app->request->post())) {
			if ($user = $model->signup()) {
				if (Yii::$app->getUser()->login($user)) {
					return $this->controller->goHome();
				}
			}
		}

		return $this->controller->render($this->view, [
			'model' => $model,
		]);
	}
}