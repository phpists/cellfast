<?php
namespace common\models;

class SubscribeFeedback extends Feedback
{
	public $sendEmailNotification = false;

	public function rules()
	{
		$rules = [
			[['email'], 'required'],
			[['email'], 'email'],
		];

		return array_merge(parent::rules(), $rules);
	}

}