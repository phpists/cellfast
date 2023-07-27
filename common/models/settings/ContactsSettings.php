<?php
namespace common\models\settings;

use Yii;

/**
 * Class ContactsSettings
 * @package common\models
 *
 * @property array $contact__cellfast
 * @property array $contact__bryza
 * @property array $contact__ines
 */

class ContactsSettings extends Settings
{
	public static $SECTION = 'ContactsSettings';

	public $contact__cellfast;
	public $contact__bryza;
	public $contact__ines;

	public function attributeLabels()
	{
		return [
			'contact__cellfast' => 'Контакт (CellFast)',
			'contact__bryza'    => 'Контакт (Bryza)',
			'contact__ines'     => 'Контакт (Ines)',
		];
	}

	public function rules()
	{
		return [
			[ [ 'contact__cellfast', 'contact__bryza', 'contact__ines' ], 'each', 'rule' => [ 'safe' ] ]
		];
	}

	public function init()
	{
		parent::init();

		$this->contact__cellfast = $this->unserializeAttribute('contact__cellfast', self::$SECTION);
		$this->contact__bryza = $this->unserializeAttribute('contact__bryza', self::$SECTION);
		$this->contact__ines = $this->unserializeAttribute('contact__ines', self::$SECTION);
	}

	public function save()
	{
		$settings = Yii::$app->settings;

		$this->serializeAttribute('contact__cellfast', self::$SECTION);
		$this->serializeAttribute('contact__bryza', self::$SECTION);
		$this->serializeAttribute('contact__ines', self::$SECTION);

		$settings->clearCache();

		return true;
	}
}