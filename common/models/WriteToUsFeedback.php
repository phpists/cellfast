<?php
namespace common\models;

use Yii;

class WriteToUsFeedback extends Feedback
{
	public $sendEmailNotification = true;

	public function rules()
	{
		$rules = [
			[['name', 'email'], 'required'],
			[['email'], 'email'],
		];

		return array_merge(parent::rules(), $rules);
	}

	public function getEmailBody() {

		return "Имя: $this->name\n".
		       "Email: $this->email\n".
		       "Сообщение:\n$this->message\n".
		       "Источник: ". $this->getSource();
	}

}