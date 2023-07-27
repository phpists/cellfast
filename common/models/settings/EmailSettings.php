<?php
namespace common\models\settings;

use Yii;

/**
 * Class EmailSettings
 * @package common\models
 * @property string $email__cellfast
 * @property string $email__bryza
 * @property string $email__ines
 */

class EmailSettings extends Settings
{
	public static $SECTION = 'EmailSettings';

	public $email__cellfast;
	public $email__bryza;
	public $email__ines;

	public function attributeLabels()
	{
		return [
			'email__cellfast' => 'Email (CellFast). Пример: hello@gmail.com, smile@mail.com',
			'email__bryza'    => 'Email (Bryza). Пример: hello@gmail.com, smile@mail.com',
			'email__ines'     => 'Email (Ines). Пример: hello@gmail.com, smile@mail.com',
		];
	}

	public function rules()
	{
		return [
			[ [ 'email__cellfast', 'email__bryza', 'email__ines' ], 'each', 'rule' => [ 'safe' ] ]
		];
	}

	public function init()
	{
		parent::init();

		$settings = Yii::$app->settings;

		$this->email__cellfast = $settings->get('email__cellfast', self::$SECTION );
		$this->email__bryza = $settings->get('email__bryza', self::$SECTION );
		$this->email__ines = $settings->get('email__ines', self::$SECTION );
	}

	public function save()
	{
		$this->set( 'email__cellfast', $this->email__cellfast, self::$SECTION );
		$this->set( 'email__bryza', $this->email__bryza, self::$SECTION );
		$this->set( 'email__ines', $this->email__ines, self::$SECTION );

		return true;
	}

	protected function set( $attribute, $value, $section )
	{
		$settings = Yii::$app->settings;

		$value = str_replace( ' ', '', $value );

		$settings->set( $attribute, $value, $section, 'string' );

		$settings->clearCache();
	}
}