<?php
namespace common\models\settings;

use Yii;

class TipModalSettings extends Settings
{
	public static $SECTION = 'TipModalSettings';

	public $message;
	public $status;

	public function attributeLabels()
	{
		return [
			'message'  => 'Описание',
			'status'  => 'Статус видимости',
		];
	}

	public function rules()
	{
		return [
			[['message'], 'string'],
			[['status'], 'integer']
		];
	}

	public function init()
	{
		$settings = Yii::$app->settings;

		$this->message = $settings->get('message', self::$SECTION);
		$this->status = $settings->get('status', self::$SECTION);
	}

	public function save()
	{
		$settings = Yii::$app->settings;

		$settings->set('message', $this->message, self::$SECTION, 'string');
		$settings->set('status', $this->status, self::$SECTION, 'string');

		return true;
	}

}