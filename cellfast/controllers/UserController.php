<?php
namespace cellfast\controllers;

use noIT\user\actions\RequestPasswordResetAction;
use noIT\user\actions\ResetPasswordAction;
use noIT\user\actions\SignInAction;
use noIT\user\actions\SignUpAction;
use yii\web\Controller;

/**
 * User actions
 *
 * Class UserController
 * @package frontend\controllers
 */
class UserController extends Controller
{
	public function actions()
	{
		return [
			'signup' => [
				'class' => SignUpAction::className(),
				'view' => '@cellfast/views/user/signup',
			],
			'signin' => [
				'class' => SignInAction::className(),
				'view' => '@cellfast/views/user/signin',
			],
			'request-password-reset' => [
				'class' => RequestPasswordResetAction::className(),
				'view' => '@cellfast/views/user/requestPasswordResetToken',
			],
			'request-password' => [
				'class' => ResetPasswordAction::className(),
				'view' => '@cellfast/views/user/resetPassword',
			],
		];
	}
}