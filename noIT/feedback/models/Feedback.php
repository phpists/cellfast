<?php
namespace noIT\feedback\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "feedback".
 *
 * @property integer $id
 * @property string $ip
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $message
 * @property string $source
 * @property string $data
 * @property string $model
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Feedback extends \yii\db\ActiveRecord
{
	const STATUS_DISABLE = 0;
	const STATUS_ACTIVE = 10;

	public $_data;
	public $_source;

	public $service;

	public $subject = 'Обращение с сайта';

	public $successful;
	public $error;

	public $adminEmailFrom;
	public $adminNameFrom;
	public $adminEmailTo;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%feedback}}';
	}

	public function init()
	{
		parent::init();
		$this->model = str_replace('common\models\\', '', self::className());
		if (!$this->adminEmailFrom) {
			$this->adminEmailFrom = @Yii::$app->params['adminEmailFrom'];
		}
		if (!$this->adminNameFrom) {
			$this->adminNameFrom = @Yii::$app->params['adminNameFrom'];
		}
		if (!$this->adminEmailTo) {
			$this->adminEmailTo = @Yii::$app->params['adminEmailTo'];
		}

	}

	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => TimestampBehavior::className()
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['service', 'time_to_call'], 'safe'],
			[['date'], 'safe'],
			[['message', 'data'], 'string'],
			[['status', 'created_at'], 'integer'],
			[['ip'], 'string', 'max' => 20],
			[['source'], 'string', 'max' => 150],
			[['name', 'email', 'phone'], 'string', 'max' => 255],
			[['model'], 'string', 'max' => 150],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'date' => 'Дата',
			'ip' => 'Ip',
			'name' => 'Имя',
			'email' => 'Email',
			'phone' => 'Телефон',
			'message' => 'Сообщение',
			'data' => 'Данные',
			'model' => 'Модель',
			'status' => 'Статус обращения',
			'source' => 'Источник',
			'created_at' => 'Дата',
			'created_by' => 'Создано',
		];
	}

	public function getSuccessfull() {
		return $this->successfull;
	}

	public function setSource($source) {
		$this->_source = $source;
	}

	public function getSource() {
		if (empty($this->_source)) {
			$this->_source = Yii::$app->request->referrer;
		}
		return $this->_source;
	}

	public function setData($data) {
		$this->_data = $data;
	}

	public function getData()
	{
		if (is_string($this->_data) && $this->_data !== null) {
			$this->_data = unserialize($this->_data);
		}
		return $this->_data;
	}

	public function beforeSave($insert)
	{
		if (!$this->source) {
			$this->source = Yii::$app->request->referrer;
		}

		$this->ip = $_SERVER['REMOTE_ADDR'];

		return parent::beforeSave($insert);
	}

	public function getEmailBody() {

		return "Имя: $this->name\n".
		       "Телефон: $this->phone\n".
		       "Email: $this->email\n".
		       "Сообщение:\n$this->message\n".
		       "Источник: ". $this->getSource();
	}

	public function sendEmail()
	{
		return Yii::$app->mailer->compose()
		                        ->setTo($this->adminEmailTo)
		                        ->setFrom([$this->adminEmailFrom => $this->adminNameFrom])
		                        ->setSubject("{$this->subject} | {$this->phone}")
		                        ->setTextBody($this->getEmailBody())
		                        ->send();
	}
}
