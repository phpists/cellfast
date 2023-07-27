<?php
namespace common\models;

use Yii;

class Feedback extends \noIT\feedback\models\Feedback
{
	const SECRET_KEY = 'not-machine';

	public $secret_form_key;

	public $sendEmailNotification = true;

	public function rules()
	{
		$rules = [
			['secret_form_key', 'string', 'max' => 50],
		];

		return array_merge(parent::rules(), $rules);
	}

	public function sendEmail()
	{
		$emailAttribute = Yii::$app->emailSettingsComponent->getEmailAttribute();
		$email = Yii::$app->emailSettingsComponent->getEmailLikeArray($emailAttribute);

		return Yii::$app->mailer->compose()
		                        ->setTo($email)
		                        ->setFrom([Yii::$app->params['adminEmailFrom'] => Yii::$app->params['adminNameFrom']])
		                        ->setSubject("{$this->subject}")
		                        ->setTextBody($this->getEmailBody())
		                        ->send();
	}
}